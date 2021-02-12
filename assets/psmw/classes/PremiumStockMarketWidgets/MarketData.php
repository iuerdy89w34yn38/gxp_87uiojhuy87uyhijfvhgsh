<?php

namespace PremiumStockMarketWidgets;

/**
 * Class MarketData - wrapper class to be called from front-end to retrieve market data
 *
 * @package PremiumStockMarketWidgets
 */
class MarketData {

  function __construct() {
  }

  /**
   * Get market data for given symbols and source passed via GET request
   * multiple symbols should be passed as an array
   */
  public function get() {
    $source = isset($_REQUEST['params']['source']) ? $_REQUEST['params']['source'] : NULL;
    $type = isset($_REQUEST['params']['type']) ? $_REQUEST['params']['type'] : NULL;
    $symbols = isset($_REQUEST['params']['symbols']) ? $_REQUEST['params']['symbols'] : [];

    $queries = [];
    // Live (delayed) market data
    if ($source == 'live') {
      $fields = isset($_REQUEST['params']['fields']) ? $_REQUEST['params']['fields'] : [];
      $quoteFields = [];
      $statsFields = [];
      foreach ($fields as $field) {
        if (strpos($field, 'quote.') !== FALSE || strpos($field, 'virtual.') !== FALSE) {
          $quoteFields[] = $field;
        } else {
          $statsFields[] = $field;
        }
      }
      if (!empty($quoteFields)) {
        $queries[] = [
          'class' =>'\\' . __NAMESPACE__ . '\\QuotesMarketDataQuery',
          'args' => ['symbols' => $symbols, 'fields' => $quoteFields]
        ];
      }
      if (!empty($statsFields)) {
        $queries[] = [
          'class' =>'\\' . __NAMESPACE__ . '\\StatsMarketDataQuery',
          'args' => ['symbols' => $symbols, 'fields' => $statsFields]
        ];
      }
    // historical data for a given period (including intraday)
    } elseif ($source == 'history') {
      if ($type == 'chart') {
        $range = isset($_REQUEST['params']['chart_range']) ? $_REQUEST['params']['chart_range'] : '1y';
        $interval = isset($_REQUEST['params']['chart_interval']) ? $_REQUEST['params']['chart_interval'] : '1d';
        $queries[] = [
          'class' => '\\'.__NAMESPACE__.'\\HistoricalMarketDataQuery',
          'args' => ['symbols' => $symbols, 'queryParams' => [$range, $interval], 'dateFormat' => 'd M Y']
        ];
      } elseif ($type == 'spark') {
        $queries[] = [
          'class' => '\\'.__NAMESPACE__.'\\HistoricalMarketDataQuery',
          'args' => ['symbols' => $symbols, 'queryParams' => ['1d', '5m'], 'dateFormat' => 'Y-m-d H:i:s e']
        ];
      }
    }

    $result = [];
    foreach ($queries as $query) {
      $result = array_merge_recursive($result, (new $query['class']($query['args']))->data());
    }

    return json_encode(['success' => !empty($result) ? TRUE : FALSE, 'data' => $result]);
  }
}