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

require_once LEFEED_TRUST_PATH . '/class/AbstractListAction.class.php';

/**
 * Lefeed_EntryListAction
**/
class Lefeed_EntryListAction extends Lefeed_AbstractListAction
{
	 /**
	 * &_getHandler
	 * 
	 * @param	void
	 * 
	 * @return	Lefeed_EntryHandler
	**/
	protected function &_getHandler()
	{
		$handler =& $this->mAsset->getObject('handler', 'Entry');
		return $handler;
	}

	/**
	 * &_getFilterForm
	 * 
	 * @param	void
	 * 
	 * @return	Lefeed_EntryFilterForm
	**/
	protected function &_getFilterForm()
	{
		$filter =& $this->mAsset->getObject('filter', 'Entry',false);
		$filter->prepare($this->_getPageNavi(), $this->_getHandler());
		return $filter;
	}

	/**
	 * _getBaseUrl
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	protected function _getBaseUrl()
	{
		return Legacy_Utils::renderUri($this->mAsset->mDirname, 'entry');
	}

	/**
	 * _setupData
	 * 
	 * @param	void
	 * 
	 * @return	void
	**/
	protected function _setupData()
	{
		foreach(array_keys($this->mObjects) as $key){
			$this->mObjects[$key]->mData = $this->mObjects[$key]->getClientData();
		}
	}

	/**
	 * _parseModuleRequest
	 * 
	 * @param	string	$request
	 * 
	 * @return	array
	**/
	protected function _parseModuleRequest(/*** string ***/ $request)
	{
		return isset($request) ? explode('+', $request) : null;
	}

	/**
	 * getDefaultView
	 * 
	 * @param	void
	 * 
	 * @return	Enum
	**/
	public function getDefaultView()
	{
		$category=null;
	
		$this->mFilter =& $this->_getFilterForm();
		$this->mFilter->fetch();
	
		$handler =& $this->_getHandler();
	
		//set category request
		if($this->mFilter->getRequest('category_id') && $this->mFilter->getRequest('access_controller')){
			$category = array('dirname'=>$this->mFilter->getRequest('access_controller'), 'id'=>explode(',', $this->mFilter->getRequest('category_id')));
		}
	
		$this->mObjects = $handler->getActivities(
			$category, 
			array(
				'dirname'=>$this->_parseModuleRequest($this->mFilter->getRequest('dirname')),
				'dataname'=>$this->_parseModuleRequest($this->mFilter->getRequest('dataname'))
			),
			$this->mFilter->getRequest('uid'),
			$this->mFilter->mNavi->getPerpage(),
			$this->mFilter->mNavi->getStart()
		);
	
		$this->_setupData();
	
		return LEFEED_FRAME_VIEW_INDEX;
	}

	/**
	 * executeViewIndex
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewIndex(/*** XCube_RenderTarget ***/ &$render)
	{
		$render->setTemplateName($this->mAsset->mDirname . '_entry_list.html');
		#cubson::lazy_load_array('entry', $this->mObjects);
		$render->setAttribute('objects', $this->mObjects);
		$render->setAttribute('dirname', $this->mAsset->mDirname);
		$render->setAttribute('dataname', 'entry');
		$render->setAttribute('pageNavi', $this->mFilter->mNavi);
	}
}

?>
