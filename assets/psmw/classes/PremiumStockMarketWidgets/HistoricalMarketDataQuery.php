<?php

namespace PremiumStockMarketWidgets;

class HistoricalMarketDataQuery extends MarketDataQueryBulk {

  protected function initialize($env) {
    $serverNumber = $env->randomServer ? rand(1,2) : 1;
    $this->type = 'history';
    $this->maxCacheTime = 300; // 5 minutes
    $this->dataUrl = 'https://query'.$serverNumber.'.finance.yahoo.com/v7/finance/spark?symbols=%s&range=%s&interval=%s&indicators=close';
    $this->resultPropertyPath = 'spark.result';
  }

  public function format($rawData, $arguments, $env) {
    $data = [];
    $range = $arguments['queryParams'][0];
    $interval = $arguments['queryParams'][1];
    $dateFormat = $arguments['dateFormat'];

    foreach ($arguments['symbols'] as $symbol) {
      if (is_object($rawData->$symbol)) {
        if (isset($rawData->$symbol->history->response[0]->indicators->quote[0]->close)) {
          foreach ($rawData->$symbol->history->response[0]->indicators->quote[0]->close as $i => $price) {
            if ($price) {
              $data[$symbol]['history'][$range][$interval]['dates'][] = date($dateFormat, $rawData->$symbol->history->response[0]->timestamp[$i]);
              $data[$symbol]['history'][$range][$interval]['quotes'][] = $price;
            }
          }
          // when larger time intervals are used (e.g. 5d, 1wk etc) the last 2 quotes are equal, so the change is always 0
          if ($range != '1d' && $interval != '1d') {
            array_pop($data[$symbol]['history'][$range][$interval]['dates']);
            array_pop($data[$symbol]['history'][$range][$interval]['quotes']);
          }
          $data[$symbol]['virtual.symbol'] = $rawData->$symbol->virtual->symbol;
          $data[$symbol]['virtual.name'] = $rawData->$symbol->virtual->name;

          if (!empty($data[$symbol]['history'][$range][$interval]['quotes'])) {
            $seriesLength = count($data[$symbol]['history'][$range][$interval]['quotes']);
            if (isset($rawData->$symbol->history->response[0]->meta->previousClose)) {
              $previousClose = $rawData->$symbol->history->response[0]->meta->previousClose;
            } elseif ($seriesLength >= 1) {
              $previousClose = $data[$symbol]['history'][$range][$interval]['quotes'][$seriesLength - 2];
            } else {
              $previousClose = 0;
            }

            $data[$symbol]['quote.regularMarketPreviousClose'] = Helper::formatDecimal($previousClose, $env);
            $data[$symbol]['quote.currency'] = isset($rawData->$symbol->history->response[0]->meta->currency) ? $rawData->$symbol->history->response[0]->meta->currency : NULL;

            if ($seriesLength >= 1) {
              $data[$symbol]['quote.regularMarketPrice'] = Helper::formatDecimal($data[$symbol]['history'][$range][$interval]['quotes'][$seriesLength - 1], $env, $symbol);
              $data[$symbol]['quote.regularMarketChange'] = $previousClose > 0 ? Helper::formatDecimal($data[$symbol]['history'][$range][$interval]['quotes'][$seriesLength - 1] - $previousClose, $env, $symbol) : 0;
              $data[$symbol]['quote.regularMarketChangePercent'] = $previousClose > 0 ? Helper::formatPercent(($data[$symbol]['history'][$range][$interval]['quotes'][$seriesLength - 1] / $previousClose - 1) * 100, $env) : '0.00%';
            }
          }
        }
      }
    }
    return $data;
  }
}