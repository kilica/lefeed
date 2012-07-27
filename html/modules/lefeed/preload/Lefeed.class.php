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

if(!defined('LEGACY_ACTIVITY_DIRNAME'))
{
	define('LEGACY_ACTIVITY_DIRNAME', basename(dirname(dirname(__FILE__))));
}

Lefeed_Activity::prepare();


/**
 * Lefeed_Activity
**/
class Lefeed_Activity extends XCube_ActionFilter
{
	/**
	 * prepare
	 * 
	 * @param	void
	 * 
	 * @return	void
	**/
	public static function prepare()
	{
		$root =& XCube_Root::getSingleton();
		$instance = new Lefeed_Activity($root->mController);
		$root->mController->addActionFilter($instance);
	}

	/**
	 * preBlockFilter
	 * 
	 * @param	void
	 * 
	 * @return	void
	**/
	public function preBlockFilter()
	{
		$file = LEFEED_TRUST_PATH . '/class/LefeedDelegateFunctions.class.php';
		$this->mRoot->mDelegateManager->add('Legacy_Activity.GetActivities','Lefeed_ActivityDelegate::getActivities', $file);
		$this->mRoot->mDelegateManager->add('Legacy_Activity.AddActivity','Lefeed_ActivityDelegate::addActivity', $file);
		$this->mRoot->mDelegateManager->add('Legacy_Activity.DeleteActivity','Lefeed_ActivityDelegate::deleteActivity', $file);
	}
}

?>
