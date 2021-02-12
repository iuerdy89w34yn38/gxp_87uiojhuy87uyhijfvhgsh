<?php defined('SMW_ROOT_DIR') or die('Direct access is not allowed');?>
<div class="<?php print $widgetClass?>" <?php print $widgetDataAttributes?>>
  <table>
    <thead>
      <tr class="smw-table-header-1st">
        <th></th>
        <?php foreach ($widgetSymbols as $symbol): ?>
          <th><?php print $symbol?></th>
        <?php endforeach?>
      </tr>
      <tr class="smw-table-header-2nd">
        <th></th>
        <?php foreach ($widgetSymbols as $symbol): ?>
          <th>
            <span class="smw-market-data-field" data-field="quote.currency" data-symbol="<?php print $symbol ?>"></span>
            <span class="smw-market-data-field" data-field="quote.regularMarketPrice" data-symbol="<?php print $symbol ?>"></span>
          </th>
        <?php endforeach?>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($widgetFields as $code):?>
      <tr>
        <td><?php print $liveDataFields[$code]->name?></td>
        <?php foreach ($widgetSymbols as $symbol): ?>
          <td><span class="smw-market-data-field <?php print in_array($code,['quote.regularMarketChange','quote.regularMarketChangePercent'])?'smw-change-indicator':''?>" data-symbol="<?php print $symbol ?>" data-field="<?php print $code?>"></span></td>
        <?php endforeach?>
      </tr>
    <?php endforeach;?>
    </tbody>
  </table>
</div>