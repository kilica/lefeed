<?php
/**
 * @package lefeed
 */

if (!defined('XOOPS_ROOT_PATH')) exit();

class Lefeed_ActivityDelegate //implements Legacy_iActivityDelegate
{
	/**
	 * addActivity
	 *
	 * @param bool		&$result
	 * @param int		$uid
	 * @param int		$categoryId
	 * @param string	$dirname
	 * @param string	$dataname
	 * @param int		$data_id
	 * @param int		$pubtime
	 *
	 * @return	void
	 */ 
	public static function addActivity(/*** bool ***/ &$result, /*** int ***/ $uid, /*** int ***/ $categoryId, /*** string ***/ $dirname, /*** string ***/ $dataname, /*** int ***/ $dataId, /*** int ***/ $pubdate)
	{
		if(! $handler = Legacy_Utils::getModuleHandler('entry', LEGACY_ACTIVITY_DIRNAME)){
			return;
		}
	
		$cri = new CriteriaCompo();
		$cri->add(new Criteria('dirname', $dirname));
		$cri->add(new Criteria('dataname', $dataname));
		$cri->add(new Criteria('data_id', $dataId));
		$objs = $handler->getObjects($cri);
		if($obj = array_shift($objs)){
			$obj->set('uid', $uid);
			$obj->set('category_id', $categoryId);
			$obj->set('pubdate', $pubdate);
			$result = $handler->insert($obj, true);
		}
		else{
			$obj = $handler->create();
			$obj->set('uid', $uid);
			$obj->set('category_id', $categoryId);
			$obj->set('dirname', $dirname);
			$obj->set('dataname', $dataname);
			$obj->set('data_id', $dataId);
			$obj->set('pubdate', $pubdate);
			$result = $handler->insert($obj, true);
		}
	}

	/**
	 * deleteActivity
	 *
	 * @param bool		&$result
	 * @param string	$dirname
	 * @param string	$dataname
	 * @param int		$data_id
	 *
	 * @return	void
	 */ 
	public static function deleteActivity(/*** bool ***/ &$result, /*** string ***/ $dirname, /*** string ***/ $dataname, /*** int ***/ $dataId)
	{
		if(! $handler = Legacy_Utils::getModuleHandler('entry', LEGACY_ACTIVITY_DIRNAME)){
			return;
		}
	
		$cri = new CriteriaCompo();
		$cri->add(new Criteria('dirname', $dirname));
		$cri->add(new Criteria('dataname', $dataname));
		$cri->add(new Criteria('data_id', $dataId));
		$objs = $handler->getObjects($cri);
		if($obj = array_shift($objs)){
			$result = $handler->delete($obj, true);
		}
	}

	/**
	 * getActivities
	 *
	 * @param mixed[]	&$list
	 *  string	$dirname
	 *  string	$dataname
	 *  int		$data_id
	 *  mixed	$data
	 *  string  $title
	 *  string	$template_name
	 * @param mixed[]	$categoryArr
	 *  string	$categoryArr['dirname']	access controller's dirname
	 *  int[]	$categoryArr['id']
	 * @param mixed[]	$moduleArr
	 *  string  $moduleArr['dirname']
	 *  string  $moduleArr['dataname']
	 * @param int		$uid
	 * @param int		$limit
	 * @param int		$start
	 *
	 * @return	void
	 */ 
	public static function getActivities(/*** mixed[] ***/ &$list, /*** mixed[] ***/ $categoryArr=null, /*** mixed[] ***/ $moduleArr=null, /*** int ***/ $uid, /*** int ***/ $limit, /*** int ***/ $start)
	{
		$handler = Legacy_Utils::getModuleHandler('entry', LEGACY_ACTIVITY_DIRNAME);
		$objs = $handler->getActivities($categoryArr, $moduleArr, $uid, $limit, $start);
		foreach($objs as $obj){
			$list[] = $obj->getClientData();
		}
	}
}

?>
