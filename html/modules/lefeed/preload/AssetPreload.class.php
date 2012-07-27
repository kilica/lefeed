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

require_once XOOPS_TRUST_PATH . '/modules/lefeed/preload/AssetPreload.class.php';
Lefeed_AssetPreloadBase::prepare(basename(dirname(dirname(__FILE__))));

?>
