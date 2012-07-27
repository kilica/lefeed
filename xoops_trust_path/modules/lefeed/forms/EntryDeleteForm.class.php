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
 * Lefeed_EntryDeleteForm
**/
class Lefeed_EntryDeleteForm extends XCube_ActionForm
{
    /**
     * getTokenName
     * 
     * @param   void
     * 
     * @return  string
    **/
    public function getTokenName()
    {
        return "module.lefeed.EntryDeleteForm.TOKEN";
    }

    /**
     * prepare
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function prepare()
    {
        //
        // Set form properties
        //
        $this->mFormProperties['entry_id'] = new XCube_IntProperty('entry_id');
    
        //
        // Set field properties
        //
        $this->mFieldProperties['entry_id'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['entry_id']->setDependsByArray(array('required'));
        $this->mFieldProperties['entry_id']->addMessage('required', _MD_LEFEED_ERROR_REQUIRED, _MD_LEFEED_LANG_ENTRY_ID);
    }

    /**
     * load
     * 
     * @param   XoopsSimpleObject  &$obj
     * 
     * @return  void
    **/
    public function load(/*** XoopsSimpleObject ***/ &$obj)
    {
        $this->set('entry_id', $obj->get('entry_id'));
    }

    /**
     * update
     * 
     * @param   XoopsSimpleObject  &$obj
     * 
     * @return  void
    **/
    public function update(/*** XoopsSimpleObject ***/ &$obj)
    {
        $obj->set('entry_id', $this->get('entry_id'));
    }
}

?>
