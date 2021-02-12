<?php defined('SMW_ROOT_DIR') or die('Direct access is not allowed');?>
<div class="<?php print $widgetClass?>" <?php print $widgetDataAttributes?>>
  <div class="smw-market-data-field" data-field="virtual.symbol"></div>
  <div>
    <span class="smw-change-indicator">
      <i class="fa fa-caret-down smw-arrow-icon smw-arrow-drop"></i>
      <i class="fa fa-caret-up smw-arrow-icon smw-arrow-rise"></i>
    </span>
    <span class="smw-market-data-field" data-field="quote.regularMarketPrice"></span>
  </div>
  <div>
    <span class="smw-market-data-field" data-field="quote.regularMarketChange"></span> (<span class="smw-market-data-field" data-field="quote.regularMarketChangePercent"></span>)
  </div>
</div>