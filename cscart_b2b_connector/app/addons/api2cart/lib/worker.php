<?php

class API2CartWorker
{
  public $rootPath = '';
  public $a2cBridgePath = '';
  public $currentFolder = '';
  public $errorMessage = '';
  public $configFilePath = '';
  public $bridgeFilePath = '';
  public $api2cartBridgePath = 'http://api.api2cart.com/v1.0/bridge.download.file';
  public $pluginPath = '';

  public function __construct()
  {
    $this->rootPath = fn_api_root_dir_path();
    $this->currentFolder = dirname(__FILE__) . '/';
    $this->a2cBridgePath = $this->rootPath . 'bridge2cart/';
    $this->configFilePath = $this->a2cBridgePath . 'config.php';
    $this->bridgeFilePath = $this->a2cBridgePath . 'bridge.php';
    $this->pluginPath = fn_plugin_api_root_dir_path();
  }

  public function getStoreKey()
  {
    if (file_exists($this->configFilePath)) {
      require_once($this->configFilePath);
      return M1_TOKEN;
    }

    return false;
  }

  public function isBridgeExist()
  {
    if (is_dir($this->a2cBridgePath)
      && file_exists($this->bridgeFilePath)
      && file_exists($this->configFilePath)
    ) {
      return true;
    }

    return false;
  }

  public function installBridge()
  {
    if ($this->isBridgeExist()) {
      return true;
    }

    file_put_contents("bridge.zip", file_get_contents($this->api2cartBridgePath));
    $zip = new ZipArchive;
    $result = false;
    $res = $zip->open("bridge.zip");
    if ($res === true) {
      $result = $zip->extractTo($this->rootPath);
      $zip->close();
    }

    chmod($this->a2cBridgePath, 0755);
    chmod($this->configFilePath, 0755);
    chmod($this->bridgeFilePath, 0755);
    unlink($this->rootPath . 'bridge.zip');

    return $result;
  }

  public function unInstallBridge()
  {
    if (!$this->isBridgeExist()) {
      return true;
    }

    return $this->deleteDir($this->a2cBridgePath);
  }

  public function updateToken($token)
  {
    $config = @fopen($this->configFilePath, 'w');
    $write = fwrite($config, "<?php define('M1_TOKEN', '" . $token . "');");

    if (($config === false) || ($write === false) || (fclose($config) === false)) {
      return false;
    }

    return true;
  }

  public static function deleteDir($dirPath) {
    if (!is_dir($dirPath)) {
      return false;
    }

    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
      $dirPath .= '/';
    }

    $files = glob($dirPath . '*', GLOB_MARK);

    foreach ($files as $file) {
      if (is_dir($file)) {
        self::deleteDir($file);
      } else {
        unlink($file);
      }
    }

    return rmdir($dirPath);
  }
}