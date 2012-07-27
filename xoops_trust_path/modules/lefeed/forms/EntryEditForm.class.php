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

require_once XOOPS_ROOT_PATH . '/core/XCube_ActionForm.class.php';
require_once XOOPS_MODULE_PATH . '/legacy/class/Legacy_Validator.class.php';

/**
 * Lefeed_EntryEditForm
**/
class Lefeed_EntryEditForm extends XCube_ActionForm
{
	/**
	 * getTokenName
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	public function getTokenName()
	{
		return "module.lefeed.EntryEditForm.TOKEN";
	}

	/**
	 * prepare
	 * 
	 * @param	void
	 * 
	 * @return	void
	**/
	public function prepare()
	{
		//
		// Set form properties
		//
		$this->mFormProperties['entry_id'] = new XCube_IntProperty('entry_id');
		$this->mFormProperties['uid'] = new XCube_IntProperty('uid');
		$this->mFormProperties['category_id'] = new XCube_IntProperty('category_id');
		$this->mFormProperties['dirname'] = new XCube_StringProperty('dirname');
		$this->mFormProperties['dataname'] = new XCube_StringProperty('dataname');
		$this->mFormProperties['data_id'] = new XCube_IntProperty('data_id');
		$this->mFormProperties['pubdate'] = new XCube_IntProperty('pubdate');

	
		//
		// Set field properties
		//
		$this->mFieldProperties['entry_id'] = new XCube_FieldProperty($this);
$this->mFieldProperties['entry_id']->setDependsByArray(array('required'));
$this->mFieldProperties['entry_id']->addMessage('required', _MD_LEFEED_ERROR_REQUIRED, _MD_LEFEED_LANG_ENTRY_ID);
		$this->mFieldProperties['uid'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['category_id'] = new XCube_FieldProperty($this);
$this->mFieldProperties['category_id']->setDependsByArray(array('required'));
$this->mFieldProperties['category_id']->addMessage('required', _MD_LEFEED_ERROR_REQUIRED, _MD_LEFEED_LANG_CATEGORY_ID);
		$this->mFieldProperties['dirname'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['dirname']->setDependsByArray(array('required','maxlength'));
		$this->mFieldProperties['dirname']->addMessage('required', _MD_LEFEED_ERROR_REQUIRED, _MD_LEFEED_LANG_DIRNAME);
		$this->mFieldProperties['dirname']->addMessage('maxlength', _MD_LEFEED_ERROR_MAXLENGTH, _MD_LEFEED_LANG_DIRNAME, '25');
		$this->mFieldProperties['dirname']->addVar('maxlength', '25');
		$this->mFieldProperties['dataname'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['dataname']->setDependsByArray(array('required','maxlength'));
		$this->mFieldProperties['dataname']->addMessage('required', _MD_LEFEED_ERROR_REQUIRED, _MD_LEFEED_LANG_DATANAME);
		$this->mFieldProperties['dataname']->addMessage('maxlength', _MD_LEFEED_ERROR_MAXLENGTH, _MD_LEFEED_LANG_DATANAME, '25');
		$this->mFieldProperties['dataname']->addVar('maxlength', '25');
		$this->mFieldProperties['data_id'] = new XCube_FieldProperty($this);
$this->mFieldProperties['data_id']->setDependsByArray(array('required'));
$this->mFieldProperties['data_id']->addMessage('required', _MD_LEFEED_ERROR_REQUIRED, _MD_LEFEED_LANG_DATA_ID);
		$this->mFieldProperties['pubdate'] = new XCube_FieldProperty($this);
	}

	/**
	 * load
	 * 
	 * @param	XoopsSimpleObject  &$obj
	 * 
	 * @return	void
	**/
	public function load(/*** XoopsSimpleObject ***/ &$obj)
	{
		$this->set('entry_id', $obj->get('entry_id'));
		$this->set('uid', $obj->get('uid'));
		$this->set('category_id', $obj->get('category_id'));
		$this->set('dirname', $obj->get('dirname'));
		$this->set('dataname', $obj->get('dataname'));
		$this->set('data_id', $obj->get('data_id'));
		$this->set('pubdate', $obj->get('pubdate'));
	}

	/**
	 * update
	 * 
	 * @param	XoopsSimpleObject  &$obj
	 * 
	 * @return	void
	**/
	public function update(/*** XoopsSimpleObject ***/ &$obj)
	{
		$obj->set('category_id', $this->get('category_id'));
		$obj->set('dirname', $this->get('dirname'));
		$obj->set('dataname', $this->get('dataname'));
		$obj->set('data_id', $this->get('data_id'));
	}

	/**
	 * _makeUnixtime
	 * 
	 * @param	string	$key
	 * 
	 * @return	void
	**/
	protected function _makeUnixtime($key)
	{
		$timeArray = explode('-', $this->get($key));
		return mktime(0, 0, 0, $timeArray[1], $timeArray[2], $timeArray[0]);
	}
}

?>
