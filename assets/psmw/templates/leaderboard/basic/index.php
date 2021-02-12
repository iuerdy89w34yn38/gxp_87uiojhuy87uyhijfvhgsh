<?php defined('SMW_ROOT_DIR') or die('Direct access is not allowed');?>
<div class="<?php print $widgetClass?>" <?php print $widgetDataAttributes?>>
  <div class="smw-header">
    <div class="smw-header-left">
      <span class="smw-market-data-field" data-field="virtual.name"></span>
      <span class="smw-market-data-field" data-field="virtual.symbol"></span>
    </div>
    <div class="smw-header-right">
      <span class="smw-market-data-field" data-field="quote.currency"></span> <span class="smw-market-data-field" data-field="quote.regularMarketPrice"></span>
      <span class="smw-change-indicator">
        <i class="fa fa-long-arrow-down smw-arrow-icon smw-arrow-drop"></i>
        <i class="fa fa-long-arrow-up smw-arrow-icon smw-arrow-rise"></i>
      </span>
    </div>
  </div>
  <div class="smw-separator"> </div>
  <div class="smw-info">
    <div class="smw-info-left">
      <span>52 week Range</span>
      <span class="smw-market-data-field" data-field="quote.fiftyTwoWeekLow"></span>
      <span> - </span>
      <span class="smw-market-data-field" data-field="quote.fiftyTwoWeekHigh"></span>
    </div>
    <div class="smw-info-right">
      <span class="smw-market-data-field smw-change-indicator" data-field="quote.regularMarketChange"></span>
      <span> / </span>
      <span class="smw-market-data-field smw-change-indicator" data-field="quote.regularMarketChangePercent"></span>
    </div>
  </div>
</div>