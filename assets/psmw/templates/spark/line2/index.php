<?php defined('SMW_ROOT_DIR') or die('Direct access is not allowed');?>
<div id="<?php print $widgetId?>" class="<?php print $widgetClass?>" <?php print $widgetDataAttributes?>>
  <div>
    <span class="smw-market-data-field" data-field="virtual.symbol"></span>
  </div>
  <div>
    <span class="smw-change-indicator">
      <i class="fa fa-arrow-down smw-arrow-icon smw-arrow-drop"></i>
      <i class="fa fa-arrow-up smw-arrow-icon smw-arrow-rise"></i>
    </span>
    <span class="smw-market-data-field smw-last-trade" data-field="quote.regularMarketPrice"></span>
    <span class="smw-spark-chart"></span>
  </div>
  <div>
    <span class="smw-market-data-field smw-change-indicator" data-field="quote.regularMarketChange"></span><span> | </span><span class="smw-market-data-field smw-change-indicator" data-field="quote.regularMarketChangePercent"></span>
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
            type: 'line',
            lineColor: '<?php print $shortcode['color']?>',
            fillColor: '#eaeaea',
            minSpotColor: false,
            maxSpotColor: false,
            spotColor: '<?php print $shortcode['color']?>',
            highlightSpotColor: '<?php print $shortcode['color']?>',
            spotRadius: 3,
            tooltipChartTitle: '<?php print $shortcode['symbol']?>',
            height: '1.5rem',
            width: '5rem',
            tooltipFormat: '<span style="color: {{color}}">&#9679;</span><span> {{offset:offset}}: <b style="font-size: 1.2em">{{y}}</b></span>',
            tooltipValueLookups: {
              offset: dates
            }
          }
        );
      });
    });
  })(jQuery);
</script>