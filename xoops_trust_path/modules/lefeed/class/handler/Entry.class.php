<?php
/**
 * @file
 * @package lefeed
 * @version $Id$
**/

if(!defined('XOOPS_ROOT_PATH'))
{
	exit;
}

/**
 * Lefeed_EntryObject
**/
class Lefeed_EntryObject extends XoopsSimpleObject
{
	public $mPrimary = 'entry_id';
	public $mDataname = 'entry';

	/**
	 * __construct
	 * 
	 * @param	void
	 * 
	 * @return	void
	**/
	public function __construct()
	{
		$this->initVar('entry_id', XOBJ_DTYPE_INT, '', false);
		$this->initVar('uid', XOBJ_DTYPE_INT, '', false);
		$this->initVar('category_id', XOBJ_DTYPE_INT, '', false);
		$this->initVar('dirname', XOBJ_DTYPE_STRING, '', false, 25);
		$this->initVar('dataname', XOBJ_DTYPE_STRING, '', false, 25);
		$this->initVar('data_id', XOBJ_DTYPE_INT, '', false);
		$this->initVar('pubdate', XOBJ_DTYPE_INT, time(), false);
	}

	/**
	 * getClientData
	 * 
	 * @param	void
	 * 
	 * @return	mixed[]
	**/
	public function getClientData()
	{
		$data = array('dirname'=>array(), 'dataname'=>array(), 'data_id'=>array(), 'data'=>array(), 'title'=>array(), 'template_name'=>array());
		XCube_DelegateUtils::call('Legacy_ActivityClient.'.$this->get('dirname').'.GetClientData', new XCube_Ref($data), $this->get('dirname'), $this->get('dataname'), $this->get('data_id'));
		return $data;
	}

	/**
	 * getClientFeed
	 * 
	 * @param	void
	 * 
	 * @return	mixed[]
	**/
	public function getClientFeed()
	{
		$data = array('title'=>null, 'link'=>null, 'id'=>null, 'published'=>null, 'updated'=>null, 'author'=>null, 'content'=>null);
		XCube_DelegateUtils::call('Legacy_ActivityClient.'.$this->get('dirname').'.GetClientFeed', new XCube_Ref($data), $this->get('dirname'), $this->get('dataname'), $this->get('data_id'));
		return $data;
	}

	/**
	 * getPrimary
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	public function getPrimary()
	{
		return self::PRIMARY;
	}

	/**
	 * getDataname
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	public function getDataname()
	{
		return self::DATANAME;
	}
}

/**
 * Lefeed_EntryHandler
**/
class Lefeed_EntryHandler extends XoopsObjectGenericHandler
{
	public /*** string ***/ $mTable = '{dirname}_entry';

	public /*** string ***/ $mPrimary = 'entry_id';

	public /*** string ***/ $mClass = 'Lefeed_EntryObject';

	/**
	 * __construct
	 * 
	 * @param	XoopsDatabase  &$db
	 * @param	string	$dirname
	 * 
	 * @return	void
	**/
	public function __construct(/*** XoopsDatabase ***/ &$db,/*** string ***/ $dirname)
	{
		$this->mTable = strtr($this->mTable,array('{dirname}' => $dirname));
		parent::XoopsObjectGenericHandler($db);
	}

	/**
	 * filter and get activities
	 *
	 * @param mixed[]	$categoryArr
	 *  string	$categoryArr['dirname']	access_controller's dirname
	 *  int[]	$categoryArr['id']
	 * @param mixed		$moduleArr
	 *  string	$moduleArr['dirname']
	 *  string	$moduleArr['dataname']
	 * @param int		$uid
	 * @param int		$limit
	 * @param int		$start
	 *
	 * @return	Lefeed_EntryObject[]
	 */ 
	public function getActivities(/*** int ***/ $categoryArr=null, /*** mixed[] ***/ $moduleArr=null, /*** int ***/ $uid=null, /*** int ***/ $limit=10, /*** int ***/ $start=0)
	{
		$hasMidCriteria = false;
		$clients = array();
	
		$midCri = new CriteriaCompo();
		XCube_DelegateUtils::call('Legacy_ActivityClient.GetClientList', new XCube_Ref($clients));
		foreach($clients as $client){
			if(! $client['access_controller']){
				continue;
			}
			$ids = $this->_getCategoryIdList($client['access_controller'], $client['dirname'], $client['dataname']);
		
			if(isset($categoryArr)){	//get specific category's data only
				if($categoryArr['dirname']==$client['access_controller']){
					$ids = array_intersect($ids, $categoryArr['id']);
				}
				else{	//skip to get this dirname/dataname data
					continue;
				}
			}
			$subCri = new CriteriaCompo();
			$subCri->add(new Criteria('dirname', $client['dirname']));
			$subCri->add(new Criteria('dataname', $client['dataname']));
			$subCri->add(new Criteria('category_id', $ids, 'IN'));
			$midCri->add($subCri, 'OR');
			unset($subCri);
			$hasMidCriteria = true;
		}
	
		$cri = new CriteriaCompo();
		if(count($moduleArr['dirname'])>0 && count($moduleArr['dirname'])===count($moduleArr['dataname'])){
			$moduleArrCri = new CriteriaCompo();
			foreach(array_keys($moduleArr['dirname']) as $key){
				$moduleCri = new CriteriaCompo();
				$moduleCri->add(new Criteria('dirname', $moduleArr['dirname'][$key]));
				$moduleCri->add(new Criteria('dataname', $moduleArr['dataname'][$key]));
				$moduleArrCri->add($moduleCri, 'OR');
			}
			$cri->add($moduleArrCri);
		}
		if($uid>0){
			$cri->add(new Criteria('uid', $uid));
		}
	
		if($hasMidCriteria===true){
			$cri->add($midCri);
		}
		$cri->setSort('pubdate', 'DESC');
		return $this->getObjects($cri, $limit, $start);
	}

	/**
	 * get permitted id list
	 *
	 * @param string	$cDirname	category module's dirname
	 * @param string	$dirname
	 * @param string	$dataname
	 *
	 * @return	int[]
	 */ 
	protected function _getCategoryIdList($cDirname, $dirname, $dataname)
	{
		$ids = array();
		$handler = xoops_gethandler('module');
		$accessController = $handler->getByDirname($cDirname);
		if(! $accessController){
			$ids[] = 0;
			return $ids;
		}
	
		switch($accessController->get('role')){
		case 'cat':
			XCube_DelegateUtils::call('Legacy_Category.'.$cDirname.'.GetPermittedIdList', new XCube_Ref($ids), $cDirname, 'viewer', Legacy_Utils::getUid());
			break;
		case 'group':
			XCube_DelegateUtils::call('Legacy_Group.'.$cDirname.'.GetGroupIdListByAction', new XCube_Ref($ids), $cDirname, $dirname, $dataname, 'view');
			break;
		default:
			$ids[] = 0;
			break;
		}
		return $ids;
	}
}

?>
