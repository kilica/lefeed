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

require_once LEFEED_TRUST_PATH . '/class/AbstractEditAction.class.php';

/**
 * Lefeed_AbstractDeleteAction
**/
abstract class Lefeed_AbstractDeleteAction extends Lefeed_AbstractEditAction
{
    /**
     * _isEnableCreate
     * 
     * @param   void
     * 
     * @return  bool
    **/
    protected function _isEnableCreate()
    {
        return false;
    }

    /**
     * _getActionName
     * 
     * @param   void
     * 
     * @return  string
    **/
    protected function _getActionName()
    {
        return _DELETE;
    }

    /**
     * prepare
     * 
     * @param   void
     * 
     * @return  bool
    **/
    public function prepare()
    {
        return parent::prepare() && is_object($this->mObject);
    }

    /**
     * _doExecute
     * 
     * @param   void
     * 
     * @return  Enum
    **/
    protected function _doExecute()
    {
        if($this->mObjectHandler->delete($this->mObject))
        {
            return LEFEED_FRAME_VIEW_SUCCESS;
        }
    
        return LEFEED_FRAME_VIEW_ERROR;
    }
}

?>
