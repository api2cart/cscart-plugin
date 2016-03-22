<?php

if (!defined('AREA')) {
  die('Access denied');
}

if (!empty($_REQUEST['addon']) && $_REQUEST['addon'] == 'api2cart') {
  if ($mode == 'update') {
    if ($_REQUEST['addon'] == 'api2cart') {
      return array(CONTROLLER_STATUS_OK, "api.connector&addon=api2cart");
    }
  }
}


