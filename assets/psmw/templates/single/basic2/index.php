<?php defined('SMW_ROOT_DIR') or die('Direct access is not allowed');?>
<div class="<?php print $widgetClass?>" <?php print $widgetDataAttributes?>>
  <div class="smw-change-indicator">
    <div class="smw-market-data-field" data-field="virtual.name"></div>
    <div>
      <span class="smw-market-data-field" data-field="virtual.symbol"></span>
      <span class="smw-market-data-field smw-last-trade" data-field="quote.regularMarketPrice"></span>
      <span class="smw-change-indicator">
        <i class="fa fa-caret-down smw-arrow-icon smw-arrow-drop"></i>
        <i class="fa fa-caret-up smw-arrow-icon smw-arrow-rise"></i>
      </span>
      <span class="smw-market-data-field smw-change-indicator" data-field="quote.regularMarketChange"></span> <span class="smw-market-data-field smw-change-indicator" data-field="quote.regularMarketChangePercent"></span>
    </div>
  </div>
</div>