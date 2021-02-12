<?php

namespace PremiumStockMarketWidgets;

class QuotesMarketDataQuery extends MarketDataQueryBulk {

  protected function initialize($env) {
    $serverNumber = $env->randomServer ? rand(1,2) : 1;
    $this->type = 'quote';
    $this->maxCacheTime = 300; // 5 minutes
    $this->dataUrl = 'https://query'.$serverNumber.'.finance.yahoo.com/v7/finance/quote?formatted=false&symbols=%s&fields=shortName,fullExchangeName,regularMarketPrice,regularMarketChange,regularMarketChangePercent,regularMarketDayLow,regularMarketDayHigh,regularMarketVolume,fiftyTwoWeekLow,fiftyTwoWeekHigh,fiftyTwoWeekLowChange,fiftyTwoWeekLowChangePercent,fiftyTwoWeekHighChange,fiftyTwoWeekHighChangePercent,regularMarketOpen,regularMarketPreviousClose,averageDailyVolume3Month,bid,ask,currency';
    $this->resultPropertyPath = 'quoteResponse.result';
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