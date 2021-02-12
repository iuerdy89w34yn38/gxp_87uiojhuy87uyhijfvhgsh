<?php defined('SMW_ROOT_DIR') or die('Direct access is not allowed');?>
<div class="<?php print $widgetClass?>" <?php print $widgetDataAttributes?>>
  <div class="smw-header">
    <span class="smw-market-data-field smw-change-indicator" data-field="virtual.name"></span>
    <span class="smw-market-data-field smw-change-indicator" data-field="quote.regularMarketChangePercent"></span>
  </div>
  <div>
    <span class="smw-change-indicator">
      <i class="fa fa-long-arrow-down smw-arrow-icon smw-arrow-drop"></i>
      <i class="fa fa-long-arrow-up smw-arrow-icon smw-arrow-rise"></i>
    </span>
    <span class="smw-market-data-field" data-field="quote.currency"></span><span class="smw-market-data-field" data-field="quote.regularMarketPrice"></span>
    <span>/</span>
    <span class="smw-market-data-field smw-change-indicator" data-field="quote.regularMarketChange"></span>
  </div>
</div>