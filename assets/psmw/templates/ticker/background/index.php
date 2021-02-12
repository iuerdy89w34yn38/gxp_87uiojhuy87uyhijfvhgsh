<?php defined('SMW_ROOT_DIR') or die('Direct access is not allowed');?>
<div id="<?php print $widgetId;?>" class="<?php print $widgetClass?>" <?php print $widgetDataAttributes?>>
  <?php foreach ($widgetSymbols as $symbol): ?>
    <span class="smw-ticker-single-container">
      <span class="smw-change-indicator" data-symbol="<?php print $symbol ?>">
        <i class="fa fa-caret-down smw-arrow-icon smw-arrow-drop"></i>
        <i class="fa fa-caret-up smw-arrow-icon smw-arrow-rise"></i>
      </span>
      <span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="virtual.name"></span>
      <span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="quote.regularMarketPrice"></span>
      <span>(</span>
      <span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="quote.regularMarketChange"></span>
      <span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="quote.regularMarketChangePercent"></span>
      <span>)</span>
    </span>
  <?php endforeach;?>
</div>
<script>
  (function ($) {
    $(document).ready(function() {
      var $widget = $('#<?php print $widgetId?>');
      $widget.one('psmwReadyGlobal', function (event) {
        $widget.marquee();
      });
    });
  })(jQuery);
</script>