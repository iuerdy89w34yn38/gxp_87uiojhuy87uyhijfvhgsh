<?php defined('SMW_ROOT_DIR') or die('Direct access is not allowed');?>
<div class="<?php print $widgetClass?>" <?php print $widgetDataAttributes?>>
  <div>
    <span class="smw-market-data-field" data-field="virtual.name"></span>
    (<span class="smw-market-data-field" data-field="virtual.symbol"></span>)
  </div>
  <div>
    <span class="smw-market-data-field" data-field="quote.regularMarketPrice"></span>
    <span class="smw-change-indicator">
      <i class="fa fa-arrow-down smw-arrow-icon smw-arrow-drop"></i>
      <i class="fa fa-arrow-up smw-arrow-icon smw-arrow-rise"></i>
    </span>
    <span class="smw-market-data-field smw-change-indicator" data-field="quote.regularMarketChange"></span> (<span class="smw-market-data-field smw-change-indicator" data-field="quote.regularMarketChangePercent"></span>)
  </div>
</div>