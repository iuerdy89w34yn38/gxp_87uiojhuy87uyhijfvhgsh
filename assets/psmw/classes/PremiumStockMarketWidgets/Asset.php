<?php

namespace PremiumStockMarketWidgets;

class Asset {
  const DATA_FILE = 'config/assets.json';
  private static $factory;
  private $assets;

  private function __construct() {
    $this->assets = Helper::loadJSON(self::DATA_FILE);
  }

  // Magic method clone is empty to prevent cloning of single instance
  protected function __clone() {}

  public static function get() {
    if (!self::$factory) {
      self::$factory = new self();
    }
    return self::$factory->assets;
  }

  public static function save($assets) {
    self::$factory->assets = $assets;
    return Helper::saveJSON(self::DATA_FILE, $assets);
  }

  public static function lookup($symbol) {
    return isset(self::$factory->assets->$symbol) ? self::$factory->assets->$symbol : NULL;
  }
}