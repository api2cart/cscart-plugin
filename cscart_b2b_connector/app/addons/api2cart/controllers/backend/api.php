<?php
/*-----------------------------------------------------------------------------+
 | MagneticOne                                                                  |
 | Copyright (c) 2008 MagneticOne.com <contact@magneticone.com>                 |
 | All rights reserved                                                          |
 +------------------------------------------------------------------------------+
 | PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "license.txt"|
 | FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE   |
 | AT THE FOLLOWING URL: http://www.magneticone.com/store/license.php           |
 |                                                                              |
 | THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE  |
 | THIS SOFTWARE   PROGRAM   AND  ASSOCIATED  DOCUMENTATION   THAT  MAGNETICONE |
 | (hereinafter  referred to as "THE AUTHOR") IS FURNISHING  OR MAKING          |
 | AVAILABLE TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").    |
 | PLEASE   REVIEW   THE  TERMS  AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT  |
 | CAREFULLY   BEFORE   INSTALLING   OR  USING  THE  SOFTWARE.  BY INSTALLING,  |
 | COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,  YOU  AND  YOUR  COMPANY  |
 | (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND AGREEING  TO  THE TERMS OF THIS  |
 | LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND BY THIS  |
 | AGREEMENT, DO  NOT INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND  |
 | OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.  THIS  |
 | AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO  USE  |
 | THE  SOFTWARE   AND  NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE. |
 | THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.       |
 |                                                                              |
 | The Developer of the Code is MagneticOne,                                    |
 | Copyright (C) 2006 - 2016 All Rights Reserved.                            |
 +-----------------------------------------------------------------------------*/

/**
  * @package  api2cart
  * @author   Vasul Babiy (v.babyi@magneticone.com)
  * @license  Not public license
  * @link     https://www.api2cart.com
  */

use Tygh\Registry;

if (!defined('AREA')) {
  die('Access denied');
}

if ($_REQUEST['addon'] == 'api2cart') {
  require(Registry::get('config.dir.addons') . 'api2cart/lib/worker.php');
  $apiWorker = new API2CartWorker();

  if ($mode == 'connector') {
    $showButton = 'install';

    if ($apiWorker->isBridgeExist()) {
      $showButton = 'uninstall';
    }

    $view = fn_api_get_view_object();
    $view->assign('publicSiteUrl', 'https://www.api2cart.com');
    $view->assign('storeKey', $apiWorker->getStoreKey());
    $view->assign('showButton', $showButton);

  } elseif ($mode == 'ajaxConnector') {
    $params = $_REQUEST;
    $storeKey = md5('api2cart_' . time());

    if (defined('AJAX_REQUEST')) {
      $returnData = array(
        'install' => false,
        'storeKeyUpdate' => false,
        'storeKey' => $storeKey,
        'remove' => false,
      );

      switch ($params['type']) {
        case 'installBridge':
          $returnData['install'] = $apiWorker->installBridge();
          $returnData['storeKeyUpdate'] = $apiWorker->updateToken($storeKey);
          break;

        case 'removeBridge':
          $returnData['remove'] = $apiWorker->unInstallBridge();
          break;

        case 'updateToken':
          $returnData['storeKeyUpdate'] = $apiWorker->updateToken($storeKey);
      }

      $view = fn_api_get_view_object();
      $view->assign('showButtonResult', json_encode($returnData));
      $view->display('addons/api2cart/views/api/connector.tpl');
    }
  }
}


