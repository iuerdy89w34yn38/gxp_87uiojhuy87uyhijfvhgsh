<?php defined('SMW_ROOT_DIR') or die('Direct access is not allowed');?>
<div id="<?php print $widgetId;?>" class="<?php print $widgetClass?>" <?php print $widgetDataAttributes?>>
  <table>
    <thead>
      <tr>
      <?php foreach ($widgetFields as $code):?>
        <?php if ($code=='quote.regularMarketPrice'):?>
          <th class="smw-tablesort smw-Float">Purchase price</th>
        <?php endif ?>
        <th<?php print isset($liveDataFields[$code]->format)?' class="smw-tablesort smw-'.$liveDataFields[$code]->format.'"':''?>><?php print $liveDataFields[$code]->name?></th>
      <?php endforeach?>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($widgetSymbols as $i => $symbol): ?>
      <tr>
      <?php foreach ($widgetFields as $code):?>
        <?php if ($code=='quote.regularMarketPrice'):?>
        <td>
          <span class="smw-portfolio-asset-price" data-value="<?php print $purchasePrices[$i] ?>"><?php print \PremiumStockMarketWidgets\Helper::formatDecimal($purchasePrices[$i], $env) ?></span>
        </td>
        <td class="smw-cell-with-indicator">
          <span class="smw-change-indicator" data-symbol="<?php print $symbol ?>">
            <i class="fa fa-arrow-down smw-arrow-icon smw-arrow-drop"></i>
            <i class="fa fa-arrow-up smw-arrow-icon smw-arrow-rise"></i>
          </span>
          <span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="<?php print $code?>"></span>
        </td>
        <?php elseif(in_array($code,['quote.regularMarketChange','quote.regularMarketChangePercent'])):?>
        <td>
          <span class="smw-portfolio-asset-<?php print str_replace('.','-',$code)?>" data-symbol="<?php print $symbol ?>"></span>
        </td>
        <?php else:?>
        <td><span class="smw-market-data-field" data-symbol="<?php print $symbol ?>" data-field="<?php print $code?>"></span></td>
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
      $widget.on('psmwReadyGlobal', function (event) {
        $widget.find('.smw-portfolio-asset-quote-regularMarketChange').each(function (i, element) {
          var $element = $(element);
          var purchasePrice = $element.closest('tr').find('.smw-portfolio-asset-price').data('value') || 0;
          var lastPrice = parseFloat($element.closest('tr').find('.smw-field-quote-regularMarketPrice').data('previous-value'));
          var change = lastPrice - purchasePrice;
          $element.text(purchasePrice > 0 ? change.formatNumber() : '-');
        });
        $widget.find('.smw-portfolio-asset-quote-regularMarketChangePercent').each(function (i, element) {
          var $element = $(element);
          var purchasePrice = $element.closest('tr').find('.smw-portfolio-asset-price').data('value') || 0;
          var lastPrice = parseFloat($element.closest('tr').find('.smw-field-quote-regularMarketPrice').data('previous-value'));
          var change = 100 * (lastPrice / purchasePrice - 1);
          $element.text(purchasePrice > 0 ? change.formatNumber()+'%' : '-');
        });
        $widget.tablesort();
        $widget.find('th.smw-tablesort').data('sortBy', premiumStockMarketWidgetsPlugin.tablesortGetValue);
      });
    });
  })(jQuery);
</script>