<?php

namespace PremiumStockMarketWidgets;

class StatsMarketDataQuery extends MarketDataQuerySingle {

  protected function initialize($env) {
    $serverNumber = $env->randomServer ? rand(1,2) : 1;
    $this->type = 'stats';
    $this->maxCacheTime = 900; // 15 minutes
    $this->dataUrl = 'https://query'.$serverNumber.'.finance.yahoo.com/v10/finance/quoteSummary/%s?formatted=false&modules=summaryProfile,summaryDetail,defaultKeyStatistics,financialData';
    $this->resultPropertyPath = 'quoteSummary.result';
  }

  protected function format($rawData, $arguments, $env) {
    $data = [];
    if (isset($arguments['fields'])) {
      foreach ($arguments['symbols'] as $symbol) {
        foreach ($arguments['fields'] as $field) {
          if (is_object($rawData->$symbol)) {
            $value = Helper::getObjectProperty($rawData->$symbol, $field);
            $data[$symbol][$field] =
              isset($env->liveDataFields->$field->format) && method_exists('\PremiumStockMarketWidgets\Helper', 'format' . $env->liveDataFields->$field->format) ?
                call_user_func([
                  '\PremiumStockMarketWidgets\Helper',
                  'format' . $env->liveDataFields->$field->format
                ], $value, $env, $symbol) :
                $value;
          }
        }
      }
    }
    return $data;
  }
}