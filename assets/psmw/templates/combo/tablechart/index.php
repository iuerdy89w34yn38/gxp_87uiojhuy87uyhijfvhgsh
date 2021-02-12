<?php defined('SMW_ROOT_DIR') or die('Direct access is not allowed');?>
<div id="<?php print $widgetId;?>" class="<?php print $widgetClass?>" <?php print $widgetDataAttributes?>>
  <div class="smw-chart-container">
    <?php foreach ($widgetSymbols as $i => $symbol): ?>
      <div id="<?php print $widgetId.'-'.$symbol?>" <?php print $i>0?'style="display: none;"':''?> class="smw smw-chart" data-symbol="<?php print $symbol?>" <?php print $chartWidgetsDataAttributes?>>
        <canvas width="16" height="9"></canvas>
      </div>
    <?php endforeach;?>
  </div>
  <table>
    <thead>
    <tr>
      <?php foreach ($widgetFields as $code):?>
        <th<?php print isset($liveDataFields[$code]->format)?' class="smw-tablesort smw-'.$liveDataFields[$code]->format.'"':''?>><?php print $liveDataFields[$code]->name?></th>
      <?php endforeach?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($widgetSymbols as $symbol): ?>
      <tr data-symbol="<?php print $symbol?>">
        <?php foreach ($widgetFields as $code):?>
          <?php if ($code=='quote.regularMarketPrice'):?>
            <td class="smw-cell-with-indicator">
          <span class="smw-change-indicator" data-symbol="<?php print $symbol ?>">
            <i class="fa fa-arrow-down smw-arrow-icon smw-arrow-drop"></i>
            <i class="fa fa-arrow-up smw-arrow-icon smw-arrow-rise"></i>
          </span>
              <span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="<?php print $code?>"></span>
            </td>
          <?php else:?>
            <td><span class="smw-market-data-field <?php print in_array($code,['quote.regularMarketChange','quote.regularMarketChangePercent'])?'smw-change-indicator':''?>" data-symbol="<?php print $symbol ?>" data-field="<?php print $code?>"></span></td>
          <?php endif?>
        <?php endforeach?>
      </tr>
    <?php endforeach;?>
    </tbody>
  </table>
</div>
<script>
  (function ($) {
    $(document).ready(function() {
      var $widget = $('#<?php print $widgetId?>');
      var id = '<?php print $widgetId?>';
      var type = '<?php print $shortcode['chart']?>';
      var lineColor = '<?php print $shortcode['line-color']?>';
      var range = $widget.data('range');
      var interval = $widget.data('interval');
      var marketData = premiumStockMarketWidgetsPlugin.getMarketData();

      $(document).on('mouseenter', '#' + id + ' table tbody tr', function () {
        var $tableRow = $(this);
        var symbol = $tableRow.data('symbol');
        var $chartContainer = $tableRow.closest('.smw-combo').find('.smw-chart-container');
        $chartContainer.find('.smw-chart').hide();
        $chartContainer.find('.smw-chart[data-symbol="'+symbol+'"]').show();
        if (typeof marketData[symbol] != 'undefined') {
          displayChart(id, symbol, type, marketData[symbol]['history'][range][interval]['dates'], marketData[symbol]['history'][range][interval]['quotes'], lineColor);
        }
      });

      $widget.on('psmwReadyGlobal', function (event) {
        var $element = $(event.target);
        // this function is triggered on both chart and combo widgets
        if ($element.hasClass('smw-chart')) {
          var symbol = $element.data('symbol');
          // display chart for the first symbol in the table
          if (symbol == '<?php print $widgetSymbols[0]?>' && typeof marketData[symbol] != 'undefined' ) {
            displayChart(id, symbol, type, marketData[symbol]['history'][range][interval]['dates'], marketData[symbol]['history'][range][interval]['quotes'], lineColor);
          }
        }
      });

      function displayChart(id, symbol, type, dates, quotes, color) {
        var ctx = $('#'+id+'-'+symbol.replace(/(:|\^|\.|\[|\]|,|=)/g, "\\$1")+' canvas').get(0);
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