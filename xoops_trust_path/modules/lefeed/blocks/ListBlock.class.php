<?php
/**
 * @file
 * @package lefeed
 * @version $Id$
**/

if(!defined('XOOPS_ROOT_PATH'))
{
    exit();
}

/**
 * Lefeed_ListBlock
**/
class Lefeed_ListBlock extends Legacy_BlockProcedure
{
    /**
     * @var Lefeed_ItemsHandler
     * 
     * @private
    **/
    var $_mHandler = null;
    
    /**
     * @var Lefeed_ItmesObject
     * 
     * @private
    **/
    var $_mOject = null;
    
    /**
     * @var string[]
     * 
     * @private
    **/
    var $_mOptions = array();
    
    /**
     * prepare
     * 
     * @param   void
     * 
     * @return  bool
     * 
     * @public
    **/
    public function prepare()
    {
        return parent::prepare() && $this->_parseOptions() && $this->_setupObject();
    }
    
    /**
     * _parseOptions
     * 
     * @param   void
     * 
     * @return  bool
     * 
     * @private
    **/
    protected function _parseOptions()
    {
        $opts = explode('|',$this->_mBlock->get('options'));
        $this->_mOptions = array(
            'limit'	=> (intval($opts[0])>0 ? intval($opts[0]) : 5),
            'target'	=> $opts[1]
        );
        return true;
    }
    
    /**
     * getBlockOption
     * 
     * @param   string  $key
     * 
     * @return  string
     * 
     * @public
    **/
    public function getBlockOption($key)
    {
        return isset($this->_mOptions[$key]) ? $this->_mOptions[$key] : null;
    }
    
    /**
     * getOptionForm
     * 
     * @param   void
     * 
     * @return  string
     * 
     * @public
    **/
    public function getOptionForm()
    {
        if(!$this->prepare())
        {
            return null;
        }
		$form = '<label for="'. $this->_mBlock->get('dirname') .'block_limit">'._AD_LEFEED_LANG_LIMIT.'</label>&nbsp;:
		<input type="text" size="5" name="options[0]" id="'. $this->_mBlock->get('dirname') .'block_limit" value="'.$this->getBlockOption('limit').'" /><br />
		<label for="'. $this->_mBlock->get('dirname') .'block_target">'._AD_LEFEED_LANG_TARGET.'</label>&nbsp;:
		<input type="text" size="64" name="options[1]" id="'. $this->_mBlock->get('dirname') .'block_target" value="'.$this->getBlockOption('target').'" />';
		return $form;
    }

    /**
     * _parseTarget
     * {access_controller_dirname},{category_id1}+{category_id2}+..+{category_idn},{dirname},{dataname},{uid};
     * ex.A) 'cat,1+2+3,news,story,;'
     * ex.B) ',,news,story,1;,,news,story,1;'
     * ex.B) 'cat,1,news,story,1;cat,1,forum,topic,;cat,1,link,item,'
     * 
     * @param   string	$target
     * 
     * @return  mixed[]
    **/
	protected function _parseTarget($target)
	{
		$ret = array('categoryArr'=>array(), 'dirname'=>array(), 'dataname'=>array(), 'uid'=>array());
		$opt = explode(',', $target);
		$ret['categoryArr'] = (isset($opt[0]) && isset($opt[1])) ? array('dirname'=>$opt[0], 'id'=>explode('+', $opt[1])) : null;
		$ret['dirname'] = isset($opt[2]) ? explode('+', $opt[2]) : null;
		$ret['dataname'] = isset($opt[3]) ? explode('+', $opt[3]) : null;
		$ret['uid'] = isset($opt[4]) ? $opt[4] : null;
		return $ret;
	}

    /**
     * _setupObject
     * 
     * @param   void
     * 
     * @return  bool
     * 
     * @private
    **/
    protected function _setupObject()
    {
    	$categoryIds = null;
		$objects = array();
		$catIdArr = array();

    	//get block options
    	$limit = $this->getBlockOption('limit');
    
        //get module asset for handlers
        $asset = null;
        XCube_DelegateUtils::call(
            'Module.lefeed.Global.Event.GetAssetManager',
            new XCube_Ref($asset),
            $this->_mBlock->get('dirname')
        );
    
        $this->_mHandler =& $asset->getObject('handler','entry');
        $args = $this->_parseTarget($this->getBlockOption('target'));
        $modules = (count($args['dirname'])>0 && count($args['dataname'])>0) ? array('dirname'=>array($args['dirname']), 'dataname'=>array($args['dataname'])) : array('dirname'=>array(), 'dataname'=>array());
        $this->_mObject = $this->_mHandler->getActivities($args['categoryArr'], $modules, $args['uid'], $limit);
        foreach($this->_mObject as $obj){
        	$obj->mData = $obj->getClientData();
        }
	
        return true;
    }

    /**
     * execute
     * 
     * @param   void
     * 
     * @return  void
     * 
     * @public
    **/
    function execute()
    {
        $root = XCube_Root::getSingleton();
    
        $render = $this->getRenderTarget();
        $render->setTemplateName($this->_mBlock->get('template'));
        $render->setAttribute('block', $this->_mObject);
        $render->setAttribute('dirname', $this->_mBlock->get('dirname'));
        $renderSystem =& $root->getRenderSystem($this->getRenderSystemName());
        $renderSystem->renderBlock($render);
    }
}

?>
