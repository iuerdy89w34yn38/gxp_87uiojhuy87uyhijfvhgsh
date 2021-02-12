<?php

require_once 'init.php';

function shortcode($shortcode) {
  $env = \PremiumStockMarketWidgets\Config::get();
  // the following fields will be available in widget templates
  $widgetId = 'smw-'.rand(100000000,999999999);
  $widgetClass = 'smw smw-'.$shortcode['type']
    .(isset($shortcode['template'])?' smw-'.$shortcode['template']:'')
    .(isset($shortcode['color'])?' smw-ct-'.$shortcode['color']:'');
  $widgetSymbols = explode(',', $shortcode['symbol']);
  $widgetFields = isset($shortcode['fields']) ? explode(',', $shortcode['fields']) : [];
  $liveDataFields = (array)$env->liveDataFields;

  // general data attributes
  if ($shortcode['type'] == 'ticker') {
    $widgetDataAttributes = sprintf('data-symbol="%s" data-type="%s" data-duplicated="true" data-duration="%s" data-pauseOnHover="%s" data-direction="%s" data-dependency="marquee"', $shortcode['symbol'], $shortcode['type'], $shortcode['speed'], $shortcode['pause'], $shortcode['direction']);
  } elseif ($shortcode['type'] == 'chart') {
    $widgetDataAttributes = sprintf('data-symbol="%s" data-type="%s" data-chart="%s" data-range="%s" data-interval="%s" data-line-color="%s" data-dependency="chart"', $shortcode['symbol'], $shortcode['type'], $shortcode['chart'], $shortcode['range'], $shortcode['interval'], $shortcode['line-color']);
  } elseif ($shortcode['type'] == 'combo') {
    $widgetDataAttributes = sprintf('data-symbol="%s" data-type="%s" data-chart="%s" data-range="%s" data-interval="%s" data-line-color="%s" data-dependency="chart"', $shortcode['symbol'], $shortcode['type'], $shortcode['chart'], $shortcode['range'], $shortcode['interval'], $shortcode['line-color']);
    $chartWidgetsDataAttributes = sprintf('data-type="chart" data-chart="%s" data-range="%s" data-interval="%s" data-dependency="chart" data-source="history"', $shortcode['chart'], $shortcode['range'], $shortcode['interval']);
  } else {
    $widgetDataAttributes = sprintf('data-symbol="%s" data-type="%s"', $shortcode['symbol'], $shortcode['type']);
  }

  // dependencies
  if (in_array($shortcode['type'],  ['table',/*'combo',*/'portfolio'])) {
    $widgetDataAttributes .= ' data-dependency="tablesort"';
  } elseif ($shortcode['type'] == 'spark') {
    $widgetDataAttributes .= ' data-dependency="sparkline"';
  }

  if ($shortcode['type'] == 'portfolio') {
    $purchasePrices = explode(',', $shortcode['price']);
  }

  // data source
  if (in_array($shortcode['type'], ['spark','chart'])) {
    $useHistoricalData = TRUE;
  } else {
    $useHistoricalData = FALSE;
  }

  if ($useHistoricalData) {
    $widgetDataAttributes .= ' data-source="history"';
  } else {
    $widgetDataAttributes .= ' data-source="live"';
  }

  // it's important to use output buffering since the shortcode function should return the string and not print it directly, otherwise
  // shortcode content will be printed before post/page content
  ob_start();
  include 'templates/' . $shortcode['type'] . (isset($shortcode['template']) && $shortcode['template']!='' ? '/' .  $shortcode['template'] . '/index.php' : '/index.php');
  $html = ob_get_contents();
  ob_end_clean();
  print $html;
}
