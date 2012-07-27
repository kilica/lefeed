<?php
/**
 * @file
 * @brief The page controller in the directory
 * @package lefeed
 * @version $Id$
**/

require_once "../../mainfile.php";
$root = XCube_Root::getSingleton();

$root->mController->execute();

if(@ $_REQUEST['requested_data_name']!='atom' && @ $_REQUEST['requested_data_name']!='rss' && @ $_REQUEST['action']!='AtomList' && @ $_REQUEST['action']!='RssList'){
	require_once XOOPS_ROOT_PATH . '/footer.php';
}
else{
	$renderSystem = $root->getRenderSystem($root->mContext->mModule->getRenderSystemName());
	$renderTarget = $root->mContext->mModule->getRenderTarget();

	if (is_object($renderTarget)) {
		if ($renderTarget->getTemplateName() == null) {
			if (isset($GLOBALS['xoopsOption']['template_main'])) {
				$renderTarget->setTemplateName($GLOBALS['xoopsOption']['template_main']);
			}
		}
		
		$renderTarget->setAttribute("stdout_buffer", ob_get_contents());
	}

	ob_end_clean();
	
	if (is_object($renderTarget)) {
		$renderSystem->render($renderTarget);
	}

	print $renderTarget->getResult();

}
?>
