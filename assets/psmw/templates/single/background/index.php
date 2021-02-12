<?php defined('SMW_ROOT_DIR') or die('Direct access is not allowed');?>
<div class="<?php print $widgetClass?>" <?php print $widgetDataAttributes?>>
  <div class="smw-first-line">
    <div class="smw-company">
      <span class="smw-market-data-field" data-field="virtual.name"></span>
      (<span class="smw-market-data-field" data-field="virtual.symbol"></span>)
    </div>
  </div>
  <div class="smw-second-line">
      <span class="smw-market-data-field" data-field="c4"></span>
      <span class="smw-market-data-field" data-field="quote.regularMarketPrice"></span>
  </div>
  <div class="smw-third-line">
    <span class="smw-market-data-field" data-field="quote.regularMarketChange"></span>
    <span class="smw-market-data-field" data-field="quote.regularMarketChangePercent"></span>
    <span class="smw-change-indicator">
      <i class="fa fa-arrow-down smw-arrow-icon smw-arrow-drop"></i>
      <i class="fa fa-arrow-up smw-arrow-icon smw-arrow-rise"></i>
    </span>
  </div>
</div>