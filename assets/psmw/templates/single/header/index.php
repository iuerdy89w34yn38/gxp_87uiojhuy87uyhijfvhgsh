<?php defined('SMW_ROOT_DIR') or die('Direct access is not allowed');?>
<div class="<?php print $widgetClass?>" <?php print $widgetDataAttributes?>>
  <div class="smw-top-border"></div>
  <div class="smw-content">
    <span class="smw-market-data-field" data-field="virtual.name"></span>

    <div class="smw-market-data-field" data-field="virtual.symbol"></div>

    <div class="smw-quote-container">
      <div class="smw-rate">
        <span class="smw-change-indicator">
          <i class="fa fa-arrow-down smw-arrow-icon smw-arrow-drop"></i>
          <i class="fa fa-arrow-up smw-arrow-icon smw-arrow-rise"></i>
        </span>
        <span class="smw-market-data-field" data-field="quote.regularMarketPrice"></span>
        <span class="smw-market-data-field" data-field="c4"></span>
      </div>

      <div class="smw-change-indicator smw-change">
        <div class="smw-market-data-field" data-field="quote.regularMarketChange"></div>
        <div class="smw-market-data-field" data-field="quote.regularMarketChangePercent"></div>
      </div>
    </div>
  </div>
</div>