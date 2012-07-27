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

require_once LEFEED_TRUST_PATH . '/actions/EntryListAction.class.php';

/**
 * Lefeed_EntryFeedAction
**/
class Lefeed_AtomListAction extends Lefeed_EntryListAction
{
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
			$this->mObjects[$key]->mData = $this->mObjects[$key]->getClientFeed();
		}
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
		$render->setTemplateName($this->mAsset->mDirname . '_atom10_list.html');
		$render->setAttribute('objects', $this->mObjects);
		$render->setAttribute('dirname', $this->mAsset->mDirname);
		$render->setAttribute('dataname', 'entry');
		$render->setAttribute('channel', $this->_getChannel());
	}
}

?>
