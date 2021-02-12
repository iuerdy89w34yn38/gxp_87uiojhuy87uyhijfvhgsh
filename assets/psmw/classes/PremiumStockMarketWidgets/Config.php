<?php

namespace PremiumStockMarketWidgets;

class Config {
  const CONFIG_FILE = 'config/env.json';
  private static $factory;
  private $env;

  private function __construct() {
    $this->env = Helper::loadJSON(self::CONFIG_FILE);
  }

  // Magic method clone is empty to prevent cloning of single instance
  protected function __clone() {}

  public static function get() {
    if (!self::$factory) {
      self::$factory = new self();
    }
    return self::$factory->env;
  }

  public static function save($env) {
    self::$factory->env = $env;
    return Helper::saveJSON(self::CONFIG_FILE, $env);
  }

  public static function getCompanyName($symbol) {
    return isset(self::$factory->env->companies->$symbol) ? self::$factory->env->companies->$symbol : NULL;
  }
}