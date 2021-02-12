<?php defined('SMW_ROOT_DIR') or die('Direct access is not allowed');?>
<div class="<?php print $widgetClass?>" <?php print $widgetDataAttributes?>>
  <div class="smw-change-indicator">
    <span class="smw-field-symbolprice">
      <span class="smw-market-data-field" data-field="virtual.symbol"></span>
      <sup><span class="smw-market-data-field" data-field="quote.currency"></span></sup><span class="smw-market-data-field" data-field="quote.regularMarketPrice"></span>
    </span>
    <span class="smw-field-change">
      <span class="smw-change-indicator">
        <i class="fa fa-long-arrow-down smw-arrow-icon smw-arrow-drop"></i>
        <i class="fa fa-long-arrow-up smw-arrow-icon smw-arrow-rise"></i>
      </span>
      <span class="smw-market-data-field" data-field="quote.regularMarketChange"></span><span class="smw-market-data-field" data-field="quote.regularMarketChangePercent"></span>
    </span>
  </div>
</div>