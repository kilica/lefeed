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

require_once LEFEED_TRUST_PATH . '/class/AbstractFilterForm.class.php';

define('LEFEED_ENTRY_SORT_KEY_ENTRY_ID', 1);
define('LEFEED_ENTRY_SORT_KEY_UID', 2);
define('LEFEED_ENTRY_SORT_KEY_CATEGORY_ID', 3);
define('LEFEED_ENTRY_SORT_KEY_DIRNAME', 4);
define('LEFEED_ENTRY_SORT_KEY_DATANAME', 5);
define('LEFEED_ENTRY_SORT_KEY_DATA_ID', 6);
define('LEFEED_ENTRY_SORT_KEY_PUBDATE', 7);

define('LEFEED_ENTRY_SORT_KEY_DEFAULT', LEFEED_ENTRY_SORT_KEY_ENTRY_ID);

/**
 * Lefeed_EntryFilterForm
**/
class Lefeed_EntryFilterForm extends Lefeed_AbstractFilterForm
{
    public /*** string[] ***/ $mSortKeys = array(
 	   LEFEED_ENTRY_SORT_KEY_ENTRY_ID => 'entry_id',
 	   LEFEED_ENTRY_SORT_KEY_UID => 'uid',
 	   LEFEED_ENTRY_SORT_KEY_CATEGORY_ID => 'category_id',
 	   LEFEED_ENTRY_SORT_KEY_DIRNAME => 'dirname',
 	   LEFEED_ENTRY_SORT_KEY_DATANAME => 'dataname',
 	   LEFEED_ENTRY_SORT_KEY_DATA_ID => 'data_id',
 	   LEFEED_ENTRY_SORT_KEY_PUBDATE => 'pubdate',

    );

    /**
     * getDefaultSortKey
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function getDefaultSortKey()
    {
        return LEFEED_ENTRY_SORT_KEY_DEFAULT;
    }

    /**
     * fetch
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function fetch()
    {
        parent::fetch();
    
        $root =& XCube_Root::getSingleton();
    
		if (($value = $root->mContext->mRequest->getRequest('entry_id')) !== null) {
			$this->mNavi->addExtra('entry_id', $value);
			$this->_mCriteria->add(new Criteria('entry_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('uid')) !== null) {
			$this->mNavi->addExtra('uid', $value);
			$this->_mCriteria->add(new Criteria('uid', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('category_id')) !== null) {
			$this->mNavi->addExtra('category_id', $value);
			$this->_mCriteria->add(new Criteria('category_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('dirname')) !== null) {
			$this->mNavi->addExtra('dirname', $value);
			$this->_mCriteria->add(new Criteria('dirname', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('dataname')) !== null) {
			$this->mNavi->addExtra('dataname', $value);
			$this->_mCriteria->add(new Criteria('dataname', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('data_id')) !== null) {
			$this->mNavi->addExtra('data_id', $value);
			$this->_mCriteria->add(new Criteria('data_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('pubdate')) !== null) {
			$this->mNavi->addExtra('pubdate', $value);
			$this->_mCriteria->add(new Criteria('pubdate', $value));
		}

    
        $this->_mCriteria->addSort($this->getSort(), $this->getOrder());
    }
}

?>
