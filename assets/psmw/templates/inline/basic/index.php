<?php defined('SMW_ROOT_DIR') or die('Direct access is not allowed');?>
<span class="<?php print $widgetClass?>" <?php print $widgetDataAttributes?>><span class="smw-market-data-field" data-field="virtual.symbol"></span>&nbsp;<span class="smw-market-data-field" data-field="quote.regularMarketPrice"></span> (<span class="smw-market-data-field smw-change-indicator" data-field="quote.regularMarketChange"></span>&nbsp;<span class="smw-market-data-field smw-change-indicator" data-field="quote.regularMarketChangePercent"></span>)</span>