<?php defined('SMW_ROOT_DIR') or die('Direct access is not allowed');?>
<div class="<?php print $widgetClass?>" <?php print $widgetDataAttributes?>>
  <div class="smw-market-data-field" data-field="virtual.name"></div>
  <div class="smw-primary-info">
    <span class="smw-market-data-field" data-field="virtual.symbol"></span>
    <sup>
      <span class="smw-market-data-field" data-field="quote.currency"></span>
    </sup>
    <span class="smw-market-data-field" data-field="quote.regularMarketPrice"></span>
    <span class="smw-change-indicator">
      <i class="fa fa-arrow-down smw-arrow-icon smw-arrow-drop"></i>
      <i class="fa fa-arrow-up smw-arrow-icon smw-arrow-rise"></i>
    </span>
    <span class="smw-market-data-field" data-field="quote.regularMarketChange"></span> / <span class="smw-market-data-field" data-field="quote.regularMarketChangePercent"></span>
  </div>
  <div class="smw-secondary-info">
    <span>Volume</span>
    <span class="smw-market-data-field" data-field="quote.regularMarketVolume"></span>
    <span class="smw-separator">|</span>
    <span>Market Cap</span>
    <span class="smw-market-data-field" data-field="summaryDetail.marketCap"></span>
    <span class="smw-separator">|</span>
    <span>Shares</span>
    <span class="smw-market-data-field" data-field="defaultKeyStatistics.sharesOutstanding"></span>
    <span class="smw-separator">|</span>
    <span>PEG Ratio</span>
    <span class="smw-market-data-field" data-field="defaultKeyStatistics.pegRatio"></span>
    <span class="smw-separator">|</span>
    <span>EPS</span>
    <span class="smw-market-data-field" data-field="defaultKeyStatistics.trailingEps"></span>
  </div>
</div>