<?php defined('SMW_ROOT_DIR') or die('Direct access is not allowed');?>
<div id="<?php print $widgetId;?>" class="<?php print $widgetClass?>" <?php print $widgetDataAttributes?>>
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
      <tr>
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
      $widget.one('psmwReadyGlobal', function (event) {
        $widget.tablesort();
        $widget.find('th.smw-tablesort').data('sortBy', premiumStockMarketWidgetsPlugin.tablesortGetValue);
      });
    });
  })(jQuery);
</script>