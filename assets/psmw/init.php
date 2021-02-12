<?php

// define plugin root folder to be used by other classess
define('SMW_ROOT_DIR', dirname(__FILE__));

// register autoload function
spl_autoload_register(function ($className) {
  if (strpos($className,'PremiumStockMarketWidgets')!==FALSE) {
    require_once 'classes/' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
  }
});