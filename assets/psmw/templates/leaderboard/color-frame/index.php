<?php defined('SMW_ROOT_DIR') or die('Direct access is not allowed');?>
<div class="<?php print $widgetClass?>" <?php print $widgetDataAttributes?>>
  <div class="smw-header-left">
    <div class="smw-market-data-field" data-field="virtual.name"></div>
    <div class="smw-quote">
      <span class="smw-market-data-field" data-field="virtual.symbol"></span>
      <sup><span class="smw-market-data-field" data-field="quote.currency"></span></sup><span class="smw-market-data-field" data-field="quote.regularMarketPrice"></span>
      <span class="smw-change-indicator">
        <i class="fa fa-long-arrow-down smw-arrow-icon smw-arrow-drop"></i>
        <i class="fa fa-long-arrow-up smw-arrow-icon smw-arrow-rise"></i>
      </span>
    </div>
    <div class="smw-change-quote">
      <span class="smw-market-data-field smw-change-indicator" data-field="quote.regularMarketChange"></span>
      <span> / </span>
      <span class="smw-market-data-field smw-change-indicator" data-field="quote.regularMarketChangePercent"></span>
    </div>
  </div>
  <div class="smw-header-right">
    <table>
      <tr>
        <td>Day Range</td>
        <td><span class="smw-market-data-field" data-field="summaryDetail.dayLow"></span> - <span class="smw-market-data-field" data-field="summaryDetail.dayHigh"></span></td>
      </tr>
      <tr>
        <td>52 Week Range</td>
        <td>
          <span class="smw-market-data-field" data-field="quote.fiftyTwoWeekLow"></span>
          <span> - </span>
          <span class="smw-market-data-field" data-field="quote.fiftyTwoWeekHigh"></span>
        </td>
      </tr>
      <tr>
        <td>Market Cap</td>
        <td><span class="smw-market-data-field" data-field="summaryDetail.marketCap"></span></td>
      </tr>
      <tr>
        <td>Shares Traded</td>
        <td><span class="smw-market-data-field" data-field="defaultKeyStatistics.sharesOutstanding"></span></td>
      </tr>
      <tr>
        <td>Volume</td>
        <td><span class="smw-market-data-field" data-field="quote.regularMarketVolume"></span></td>
      </tr>
    </table>
  </div>
</div>