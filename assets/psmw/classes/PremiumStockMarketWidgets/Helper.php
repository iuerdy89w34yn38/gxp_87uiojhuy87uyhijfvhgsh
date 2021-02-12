<?php

namespace PremiumStockMarketWidgets;

/**
 * Class Helper - helper functions
 * @package PremiumStockMarketWidgets
 */
class Helper {
  const LOG_FILE = 'trace.log';
  /**
   * Print message or array/object
   * @param $msg
   */
  public static function p($msg) {
    if (is_array($msg) || is_object($msg)) {
      print '<pre>'.print_r($msg, TRUE).'</pre>';
    } else {
      print $msg;
    }
  }

  /**
   * Convert object to new line delimited key = value pairs
   * @param $object
   * @return string
   */
  public static function objectToTextarea($object) {
    $result = '';
    foreach ($object as $key => $value) {
      $result .= $key.'='.$value."\n";
    }
    return $result;
  }

  /**
   * Convert new line delimited key = value pairs to StdClass object
   * @param $string
   * @return \stdClass
   */
  public static function textareaToObject($string) {
    $result = new \stdClass();
    foreach (explode("\n",$string) as $row) {
      $row = trim($row);
      if ($row!='' && strpos($row,'=')!==FALSE) {
        list($key, $value) = explode('=',$row);
        $result->$key = $value;
      }
    }
    return $result;
  }

  /**
   * Decode JSONP
   * @param $jsonp
   * @param bool|FALSE $assoc
   * @return mixed
   */
  public static function JSONPDecode($jsonp, $assoc = false) {
    if($jsonp[0] !== '[' && $jsonp[0] !== '{') { // we have JSONP
      $jsonp = substr($jsonp, strpos($jsonp, '('));
    }
    return json_decode(trim(trim($jsonp),'(); '), $assoc);
//    return '"'.trim(trim($jsonp),'();').'"';
  }

  public static function loadJSON($fileName) {
    return file_exists(SMW_ROOT_DIR . DIRECTORY_SEPARATOR . $fileName) ?
      json_decode(file_get_contents(SMW_ROOT_DIR . DIRECTORY_SEPARATOR . $fileName)) :
      '';
  }

  public static function saveJSON($fileName, $contents) {
    return file_put_contents(SMW_ROOT_DIR . DIRECTORY_SEPARATOR . $fileName, json_encode($contents));
  }

  public static function log($msg) {
    file_put_contents(SMW_ROOT_DIR . DIRECTORY_SEPARATOR . self::LOG_FILE, sprintf("[%s]: %s\n", date(DATE_ATOM), is_string($msg) ? $msg : print_r($msg, TRUE)), FILE_APPEND);
  }

  public static function formatInt($input, $env, $symbol = NULL) {
    return is_numeric($input) ?
      number_format(
        floatval($input),
        0,
        $env->settings->numberFormat->decimalPoint,
        $env->settings->numberFormat->thousandsSeparator
      ) :
      $input;
  }

  public static function formatDecimal($input, $env, $symbol = NULL) {
    $absInput = abs($input);
    if ($symbol && self::isCurrencySymbol($symbol)) {
      $decimals =  $env->settings->numberFormat->currencyDecimals;
    } elseif (0.0001 <= $absInput && $absInput < 0.01) {
      $decimals = 4;
    } elseif ($absInput < 0.0001) {
      $decimals = 6;
    } else {
      $decimals = $env->settings->numberFormat->decimals;
    }
    // return float number as a string, otherwise 1.00 will be passed as 1 to the client side
    return number_format(
        floatval($input),
        $decimals,
        $env->settings->numberFormat->decimalPoint,
        $env->settings->numberFormat->thousandsSeparator
      );
  }

  public static function formatPercent($input, $env, $symbol = NULL) {
    return self::formatDecimal(str_replace('%','',$input), $env, $symbol).'%';
  }

  public static function formatDate($unixDate, $env, $symbol = NULL) {
    return $unixDate ? date($env->settings->dateFormat->format, $unixDate) : '-';
  }

  public static function formatDateTime($unixDate, $env, $symbol = NULL) {
    return $unixDate ? date($env->settings->dateTimeFormat->format, $unixDate) : '-';
  }

  public static function getCurrencySymbol($currency) {
    return str_replace(['USD','GBP','EUR','CAD','AUD','HKD'], ['$','£','€','C$','A$','HK$'], $currency);
  }

  public static function isCurrencySymbol($symbol) {
    return substr($symbol,-2) == '=X';
  }

  public static function toJSONString($object) {
    return htmlspecialchars(json_encode($object), ENT_QUOTES, 'UTF-8');
  }

  public static function protocol() {
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http';
  }

  /**
   * Get nested property of an object by its path, e.g. getProperty({x: {y: 1}}, 'x.y')
   * @param $object
   * @param $path
   * @return mixed
   */
  public static function getObjectProperty($object, $path) {
    return array_reduce(explode('.', $path), function ($carry, $item) {
      return isset($carry->$item) ? $carry->$item : '';
    }, $object);
  }

  public static function cleanString($jsonString) {
    if (!is_string($jsonString) || !$jsonString) return '';

    // Remove unsupported characters
    // Check http://www.php.net/chr for details
    for ($i = 0; $i <= 31; ++$i)
      $jsonString = str_replace(chr($i), "", $jsonString);

    $jsonString = str_replace(chr(127), "", $jsonString);

    // Remove the BOM (Byte Order Mark)
    // It's the most common that some file begins with 'efbbbf' to mark the beginning of the file. (binary level)
    // Here we detect it and we remove it, basically it's the first 3 characters.
    if (0 === strpos(bin2hex($jsonString), 'efbbbf')) $jsonString = substr($jsonString, 3);

    return $jsonString;
  }
}