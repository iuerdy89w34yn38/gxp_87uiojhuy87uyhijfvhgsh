<?php defined('SMW_ROOT_DIR') or die('Direct access is not allowed');?>
<div id="<?php print $widgetId?>" class="<?php print $widgetClass?>" <?php print $widgetDataAttributes?>>
  <div>
    <span class="smw-market-data-field" data-field="virtual.symbol"></span>
    <span class="smw-change-indicator">
      <i class="fa fa-long-arrow-down smw-arrow-icon smw-arrow-drop"></i>
      <i class="fa fa-long-arrow-up smw-arrow-icon smw-arrow-rise"></i>
    </span>
    <span class="smw-market-data-field" data-field="quote.currency"></span><span class="smw-market-data-field" data-field="quote.regularMarketPrice"></span>
    <span class="smw-spark-chart"></span>
  </div>
</div>
<script>
  (function ($) {
    $(document).ready(function() {
      var $widget = $('#<?php print $widgetId?>');
      var range = '1d';
      var interval = '5m';
      $widget.on('psmwReadyGlobal', function (event) {
        var marketData = premiumStockMarketWidgetsPlugin.getMarketData();
        var quotes = marketData[$widget.data('symbol')]['history'][range][interval]['quotes'].slice(-15);
        var dates = marketData[$widget.data('symbol')]['history'][range][interval]['dates'].slice(-15);
        $widget.find('.smw-spark-chart').sparkline(
          quotes, {
            type: 'bar',
            lineColor: '<?php print $shortcode['color']?>',
            barColor: '<?php print $shortcode['color']?>',
            tooltipChartTitle: '<?php print $shortcode['symbol']?>',
            height: '1.5rem',
            tooltipFormat: '<span style="color: {{color}}">&#9679;</span><span> {{offset:offset}}: <b style="font-size: 1.2em">{{value}}</b></span>',
            tooltipValueLookups: {
              offset: dates
            }
          }
        );
      });
    });
  })(jQuery);
</script>