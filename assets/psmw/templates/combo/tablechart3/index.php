<?php defined('SMW_ROOT_DIR') or die('Direct access is not allowed');?>
<div id="<?php print $widgetId;?>" class="<?php print $widgetClass?>" <?php print $widgetDataAttributes?>>
  <div class="smw-chart-container">
    <?php foreach ($widgetSymbols as $i => $symbol): ?>
    <div class="smw-modal" data-symbol="<?php print $symbol;?>">
      <div class="smw-modal-content">
        <div><span class="smw-modal-close">&times;</span></div>
        <div class="smw-modal-flex">
          <div class="smw-modal-flex-wide">
            <div class="smw-modal-buttons">
              <?php foreach([['5d','1d','5 days'],['1mo','1d','1 month'],['3mo','1d','3 months'],['6mo','1wk','6 months'],['ytd','1wk','YTD'],['1y','1mo','1 year'],['2y','1mo','2 years'],['5y','3mo','5 years'],['10y','3mo','10 years']] as $button): ?>
              <button class="smw-chart-period-switch<?php print $shortcode['range']==$button[0] && $shortcode['interval']==$button[1] ? ' smw-button-active' : ''?>" data-symbol="<?php print $symbol;?>" data-range="<?php print $button[0]?>" data-interval="<?php print $button[1]?>"><?php print $button[2]?></button>
              <?php endforeach?>
            </div>
            <div id="<?php print $widgetId.'-'.$symbol?>" class="smw smw-chart" data-symbol="<?php print $symbol?>" <?php print $chartWidgetsDataAttributes?>>
              <canvas width="16" height="9"></canvas>
            </div>
            <div class="smw-modal-flex">
              <div class="smw-modal-flex-equal smw-modal-data-container">
                <div class="smw-data-heading">Key metrics</div>
                <div><span>Market Cap</span><span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="summaryDetail.marketCap"></span></div>
                <div><span>PE Ratio</span><span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="summaryDetail.trailingPE"></span></div>
                <div><span>PEG Ratio</span><span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="defaultKeyStatistics.pegRatio"></span></div>
                <div><span>EPS</span><span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="defaultKeyStatistics.trailingEps"></span></div>
                <div><span>Beta</span><span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="summaryDetail.beta"></span></div>
              </div>
              <div class="smw-modal-flex-equal smw-modal-data-container">
                <div class="smw-data-heading">Dividends</div>
                <div><span>Dividend Rate</span><span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="summaryDetail.dividendRate"></span></div>
                <div><span>Dividend Yield</span><span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="summaryDetail.dividendYield"></span></div>
                <div><span>Ex Dividend Date</span><span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="summaryDetail.exDividendDate"></span></div>
                <div><span>50 Days Moving Average</span><span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="summaryDetail.fiftyDayAverage"></span></div>
                <div><span>200 Days Moving Average</span><span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="summaryDetail.twoHundredDayAverage"></span></div>
              </div>
            </div>
          </div>
          <div class="smw-modal-flex-short smw-modal-data-container">
            <div class="smw-data-heading">Key data</div>
            <div><span>Symbol</span><span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="virtual.symbol"></span></div>
            <div><span>Name</span><span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="virtual.name"></span></div>
            <div><span>Bid</span><span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="quote.bid"></span></div>
            <div><span>Ask</span><span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="quote.ask"></span></div>
            <div><span>Last Price</span><span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="quote.regularMarketPrice"></span></div>
            <div><span>Change</span><span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="quote.regularMarketChange"></span></div>
            <div><span>52 Week Low</span><span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="quote.fiftyTwoWeekLow"></span></div>
            <div><span>52 Week High</span><span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="quote.fiftyTwoWeekHigh"></span></div>
            <div class="smw-info">
              <span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="summaryProfile.longBusinessSummary"></span>
            </div>
          </div>
        </div>
      </div>
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
      var $document = $(document);
      var $widget = $('#<?php print $widgetId?>');
      var id = '<?php print $widgetId?>';
      var type = '<?php print $shortcode['chart']?>';
      var lineColor = '<?php print $shortcode['line-color']?>';
      var range = $widget.data('range');
      var interval = $widget.data('interval');
      var marketData = premiumStockMarketWidgetsPlugin.getMarketData();
      var charts = [];

      $document.on('click', '#' + id + ' table tbody tr', function () {
        var $tableRow = $(this);
        var symbol = $tableRow.data('symbol');
        var $chartContainer = $tableRow.closest('.smw-combo').find('.smw-chart-container');
        $chartContainer.find('.smw-modal[data-symbol="'+symbol+'"]').show();
        if (typeof marketData[symbol] != 'undefined') {
          displayChart(id, symbol, type, marketData[symbol]['history'][range][interval]['dates'], marketData[symbol]['history'][range][interval]['quotes'], lineColor);
        }
      });

      $document.on('click', '#' + id + ' .smw-chart-period-switch', function () {
        var $button = $(this);
        var s = $button.data('symbol');
        var r = $button.data('range');
        var i = $button.data('interval');
        $button.parent().children().removeClass('smw-button-active');
        $button.addClass('smw-button-active');
        if (typeof marketData[s]['history'][r] != 'undefined' && typeof marketData[s]['history'][r][i] != 'undefined') {
          displayChart(id, s, type, marketData[s]['history'][r][i]['dates'], marketData[s]['history'][r][i]['quotes'], lineColor);
        } else {
          premiumStockMarketWidgetsPlugin.retrieveMarketData($widget, {
            source: 'history',
            symbols: [s],
            type: 'chart',
            chart_range: r,
            chart_interval: i,
            callback: function () {
              marketData = premiumStockMarketWidgetsPlugin.getMarketData();
              // the callback is also called right on click, so need to check that the data exists before rendering the chart
              if (typeof marketData[s]['history'][r] != 'undefined' && typeof marketData[s]['history'][r][i] != 'undefined') {
                displayChart(id, s, type, marketData[s]['history'][r][i]['dates'], marketData[s]['history'][r][i]['quotes'], lineColor);
              }
            }
          }, true);
        }
      });

      function displayChart(id, symbol, type, dates, quotes, color) {
        if (typeof charts[symbol] != 'undefined') {
          charts[symbol].destroy();
        }
        var ctx = $('#'+id+'-'+symbol.replace(/(:|\^|\.|\[|\]|,|=)/g, "\\$1")+' canvas').get(0);
        if (ctx !== undefined) {
          charts[symbol] = new Chart(ctx, {
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
              responsive: true,
              maintainAspectRatio: true
            }
          });
        }
      }
    });
  })(jQuery);
</script>