<?php defined('SMW_ROOT_DIR') or die('Direct access is not allowed');?>
<div id="<?php print $widgetId?>" class="<?php print $widgetClass?>" <?php print $widgetDataAttributes?>>
  <canvas width="16" height="9"></canvas>
</div>
<script>
  (function ($) {
    $(document).ready(function() {
      var $widget = $('#<?php print $widgetId?>');
      var id = '<?php print $widgetId?>';
      var symbol = '<?php print $shortcode['symbol']?>';
      var lineColor = '<?php print $shortcode['line-color']?>';
      var type = '<?php print $shortcode['chart']?>';
      var range = $widget.data('range');
      var interval = $widget.data('interval');
      var marketData = premiumStockMarketWidgetsPlugin.getMarketData();
      $widget.one('psmwReadyGlobal', function (event) {
        if (typeof marketData[symbol] != 'undefined' ) {
          displayChart(id, symbol, type, marketData[symbol]['history'][range][interval]['dates'], marketData[symbol]['history'][range][interval]['quotes'], lineColor);
        }
      });

      function displayChart(id, symbol, type, dates, quotes, color) {
        var ctx = $('#'+id+' canvas').get(0);
        if (ctx !== undefined) {
          var chart = new Chart(ctx, {
            type: type,
            data: {
              labels: dates,
              datasets: [{
                label: symbol,
                data: quotes,
                borderColor: color,
                backgroundColor: (premiumStockMarketWidgetsPlugin.checkIE() ? color : color.replace('rgb','rgba').replace(')',',0.3')),
                fill: true,
//                lineTension: 0.5,
                borderWidth: 1,
                pointRadius: 0
              }]
            },
            options: {
              responsive: true
            }
          });
        }
      }
    });
  })(jQuery);
</script>