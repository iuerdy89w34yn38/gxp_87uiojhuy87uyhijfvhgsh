var smwPathToAssets = smwPathToAssets || '';
var smwGlobals = {
  debug: false,
  code: 'smw',
  ajaxUrl: smwPathToAssets + '/psmw/ajax.php',
  pluginUrl: smwPathToAssets + '/psmw/',
  dependencies: {
    marquee:   smwPathToAssets + '/psmw/vendor/jquery.marquee/jquery.marquee.min.js',
    tablesort: smwPathToAssets + '/psmw/vendor/jquery.tablesort/jquery.tablesort.min.js',
    sparkline: smwPathToAssets + '/psmw/vendor/jquery.sparkline/jquery.sparkline.min.js',
    chart:     smwPathToAssets + '/psmw/vendor/chart.js/Chart.min.js'
  }
};
"use strict";
var premiumStockMarketWidgetsPlugin = (function ($) {
  log('globals', smwGlobals);
  var code = smwGlobals.code;
  var classWidgets           =  '.' + code;
  var classMarketDataField   =  '.' + code + '-market-data-field';
  var classChangeIndicator   =  '.' + code + '-change-indicator';

  var $document, $widgetContainers;

  var loadedTemplatesCSSFiles = [];
  var vendorPluginsPromises = {};
  var vendorPlugins = [];
  var vendorPluginsLoadCounter = 0;
  var ajaxRequestsRemaining = 0;
  var marketData = {};

  var $dfMarketDataReady;
  var $dfPluginsLoaded;

  $(document).ready(function() {
    // initialize all widgets when DOM is ready
    initializeWidgets(true);
  });

  function initializeWidgets(retrieveMarketDataFromServer) {
    $document = $(document);
    $widgetContainers = $(classWidgets);
    log('Initialize', $widgetContainers.length + ' widgets');

    $dfMarketDataReady = $.Deferred();
    $dfPluginsLoaded = $.Deferred();

    $.when($dfMarketDataReady, $dfPluginsLoaded).done(function() {
      $widgetContainers.trigger('psmwReadyGlobal');
    });

    // load templates CSS files
    loadTemplatesStyles($widgetContainers);

    // exit if there are no .smw containers
    if ($widgetContainers.length==0) return false;

    // remove events, so they are not fired twice
    $document
      .off('psmwReadyGlobal', $widgetContainers)
      .off('mouseenter', classWidgets + ' .smw-combo table tbody tr');
    // register custom events
    // it's important to bind psmwReadyGlobal to document as templates may also listen to this event
    $document
      // when market data is received
      .on('psmwReadyGlobal', $widgetContainers, makeWidgetVisible)
      // hide modal
      .on('click', '.smw-modal-close', function() {
        $(this).closest('.smw-modal').hide();
      });

    // get market data and build widgets
    buildWidgets($widgetContainers, retrieveMarketDataFromServer);
  }

  function buildWidgets($widgetContainers, retrieveMarketDataFromServer) {
    loadDependencies($widgetContainers);

    var retrieveMarketDataFromServer = typeof retrieveMarketDataFromServer == 'undefined' ? true : retrieveMarketDataFromServer;
    var fields = getWidgetsFields($widgetContainers);
    var liveDataSymbols = [];
    var sparkDataSymbols = [];
    var chartDataSymbols = [];
    var $liveDataWidgetContainers = $();
    var $sparkDataWidgetContainers = $();
    var $chartDataWidgetContainers = $();
    $widgetContainers.each(function(i, widgetContainer) {
      var $widgetContainer = $(widgetContainer);
      // there could be multiple comma-delimited symbols
      var symbol        = $widgetContainer.data('symbol');
      var source        = $widgetContainer.data('source');
      var type          = $widgetContainer.data('type');
      // gather all symbols to retrieve live market data all at once
      if (source == 'live') {
        $liveDataWidgetContainers = $liveDataWidgetContainers.add($widgetContainer);
        var widgetContainerSymbols = symbol ? symbol.split(',') : [];
        for (var k = 0; k < widgetContainerSymbols.length; k++) {
          if ($.inArray(widgetContainerSymbols[k], liveDataSymbols) < 0) {
            liveDataSymbols.push(widgetContainerSymbols[k]);
          }
        }
      // retrieve data from intraday source
      } else if (source == 'history') {
        // for spark, chart, combo widgets data needs to be retrieved symbol by symbol (because there can be different ranges and intervals requested)
        if (type == 'spark') {
          sparkDataSymbols.push(symbol);
          $sparkDataWidgetContainers = $sparkDataWidgetContainers.add($widgetContainer);
        } else if (type == 'chart'/* || type == 'combo'*/) {
          chartDataSymbols.push(symbol);
          $chartDataWidgetContainers = $chartDataWidgetContainers.add($widgetContainer);
        }
      }
    });

    var sparkDataSymbolsCount = sparkDataSymbols.length;
    var chartDataSymbolsCount = chartDataSymbols.length;
    ajaxRequestsRemaining += sparkDataSymbolsCount + chartDataSymbolsCount + (liveDataSymbols.length ? 1 : 0);
    log('Number of AJAX calls to make', ajaxRequestsRemaining);

    // data for spark chart widgets
    for (var i=0; i<sparkDataSymbolsCount; i++) {
      var $sparkDataWidgetContainer = $sparkDataWidgetContainers.eq(i);
      retrieveMarketData($sparkDataWidgetContainer, {source: 'history', symbols: sparkDataSymbols[i].split(','), type: 'spark'}, retrieveMarketDataFromServer);
    }

    // data for chart, combo widgets
    for (var i=0; i<chartDataSymbolsCount; i++) {
      var $chartDataWidgetContainer = $chartDataWidgetContainers.eq(i);
      retrieveMarketData($chartDataWidgetContainer, {source: 'history', symbols: chartDataSymbols[i].split(','), type: 'chart', chart_range: $chartDataWidgetContainer.data('range'), chart_interval: $chartDataWidgetContainer.data('interval')}, retrieveMarketDataFromServer);
    }

    // retrieve live market data in one request
    if (liveDataSymbols.length && fields.length) {
      retrieveMarketData($liveDataWidgetContainers, {source: 'live', symbols: liveDataSymbols, fields: fields}, retrieveMarketDataFromServer);
    }
  }

  /**
   * Get all fields
   * @param $widgetContainers
   */
  function getWidgetsFields($widgetContainers) {
    var fields = [];
    $widgetContainers.find(classMarketDataField).each(function(i, dataField) {
      var dataField = ($(dataField).data('field')).replace('_','');
      if ($.inArray(dataField, fields)<0) {
        fields.push(dataField);
      }
    });
    return fields;
  }

  /**
   *
   * @param retrieveMarketDataFromServer
   */
  function retrieveMarketData($widgetContainers, params, retrieveMarketDataFromServer, callback) {
    if (retrieveMarketDataFromServer) {
      log('Get market data from SERVER', params.source, params);
      if (!$.isEmptyObject(params)) {
        $.ajax({
          method: smwGlobals.ajaxMethod,
          url: smwGlobals.ajaxUrl,
          dataType: 'json',
          cache: false,
          context: {
            source: params.source,
            symbol: params.symbols,
            $widgetContainers: $widgetContainers
          },
          data: {
            params: params
          },
          success: function (response) {
            log('Market data received', this.source, response);
            ajaxRequestsRemaining--;
            if (response.success) {
              // cache data, so widgets can be built using cached data
              $.extend(true, marketData, response.data);
              log('Market data cached', marketData);
              if (typeof params.callback == 'function') {
                params.callback();
              } else {
                addDataToWidgets(this.$widgetContainers, marketData);
              }
            } else {
              log('ERROR response received', response.data);
            }
          }
        });
      }
    } else {
      log('Get market data from CACHE', params.source, params);
      ajaxRequestsRemaining--;
      // add cached data to widgets
      addDataToWidgets($widgetContainers, marketData);
    }
  }

  /**
   * Load 3rd party vendor plugins if necessary
   * @param $widgetContainers
   */
  function loadDependencies($widgetContainers) {
    // loop through widgets and identify necessary JavaScript plugins
    $widgetContainers.each(function (i, widgetContainer) {
      var widgetDependencies = $(widgetContainer).data('dependency');
      if (widgetDependencies) {
        // there could be multiple dependencies
        widgetDependencies = widgetDependencies.split(',');
        for (var k = 0; k < widgetDependencies.length; k++) {
          if ($.inArray(widgetDependencies[k], vendorPlugins) < 0)
            vendorPlugins.push(widgetDependencies[k]);
        }
      }
    });

    // loop through identified JavaScript plugins and load them
    var n = vendorPlugins.length;
    if (n > vendorPluginsLoadCounter) {
      for (var m = 0; m < n; m++) {
        var pathToFile = smwGlobals.dependencies[vendorPlugins[m]];
        // if given JS file is not loaded yet
        if (typeof vendorPluginsPromises[pathToFile] == 'undefined') {
          log('Loading JS file', pathToFile);
          // save promise to global variable, so if this script is required by multiple widgets, callbacks can be attached to this promise
          vendorPluginsPromises[pathToFile] = $.ajax({
            url: pathToFile,
            dataType: 'script',
            cache: true
          }).done(function () {
            vendorPluginsLoadCounter++;
            log('JS file loaded', pathToFile);
            if (vendorPluginsLoadCounter == vendorPlugins.length) {
              log('All plugins loaded');
              $dfPluginsLoaded.resolve();
            }
          });
        }
      }
    } else {
      $dfPluginsLoaded.resolve();
    }
  }

  /**
   * Parse and add market data to all widgets
   * @param marketData
   */
  function addDataToWidgets($widgetContainers, marketData) {
    // loop through all widgets
    $widgetContainers.each(function(i, widgetContainer) {
      var $widgetContainer = $(widgetContainer);
      // loop through all fields
      $widgetContainer.find(classMarketDataField).each(function(k, widgetField) {
        var $widgetField = $(widgetField);
        var marketDataFieldCode = $widgetField.data('field');
        // get symbol either from the field itself (e.g. for tables, when each row has different symbol) or from the widget container
        var symbol = $widgetField.data('symbol') ? $widgetField.data('symbol') : $widgetContainer.data('symbol');
        var marketDataFieldValue = typeof marketData[symbol] != 'undefined' && typeof marketData[symbol][marketDataFieldCode] != 'undefined' ? marketData[symbol][marketDataFieldCode] : '';
        $widgetField.addClass('smw-field-' + marketDataFieldCode.replace('.','-'));
        $widgetField.text(marketDataFieldValue);

        var marketDataFieldPreviousValue = $widgetField.data('previous-value');
        // if value was changed pulsate the field
        if (typeof marketDataFieldPreviousValue != 'undefined' && marketDataFieldPreviousValue != marketDataFieldValue) {
          $widgetField
            .animate({opacity: 0}, 200)
            .animate({opacity: 1}, 200)
            .animate({opacity: 0}, 200)
            .animate({opacity: 1}, 200)
        }
        $widgetField.data('previous-value', marketDataFieldValue);
      });

      // add classes for up/down indicators
      $widgetContainer.find(classChangeIndicator).each(function(k, upDownIndicator) {
        var $upDownIndicator = $(upDownIndicator);
        var symbol = $upDownIndicator.data('symbol') ? $upDownIndicator.data('symbol') : $widgetContainer.data('symbol');
        var change = typeof marketData[symbol] != 'undefined' && typeof marketData[symbol]['quote.regularMarketChange'] != 'undefined' ? parseFloat(marketData[symbol]['quote.regularMarketChange']) : 0;
        if (change > 0) {
          $upDownIndicator.removeClass('smw-drop').addClass('smw-rise');
        } else if (change < 0) {
          $upDownIndicator.removeClass('smw-rise').addClass('smw-drop');
        } else {
          $upDownIndicator.removeClass('smw-drop smw-rise');
        }
      });
      // trigger individual events
      //$widgetContainer.trigger('psmwReady-'+$widgetContainer.data('type'));
    });

    // the below will trigger psmwReadyGlobal event for each of the widgets
    if (ajaxRequestsRemaining==0) {
      log('All AJAX requests completed');
      $dfMarketDataReady.resolve();
    }
  }

  /**
   * Display widgets that are already populated with data
   * @param event
   */
  function makeWidgetVisible(event) {
    // event.target is the element, which triggers the event
    var $widgetContainer = $(event.target);
    log('psmwReadyGlobal', $widgetContainer.attr('id'), $widgetContainer.attr('class'));
    // make widget visible after the data is loaded to it
    if (!$widgetContainer.hasClass('smw-visible')) {
      $widgetContainer.addClass('smw-visible');
    }
  }

  function loadTemplatesStyles($widgetContainers) {
    // loop through all widgets and dynamically load CSS styles for each template
    $widgetContainers.each(function(i, widgetContainer) {
      var widgetClasses = widgetContainer.className.split(/\s+/);
      if (widgetClasses.length>=3) {
        loadCSSFile(smwGlobals.pluginUrl + 'templates/' + widgetClasses[1].substr(4) + "/" + widgetClasses[2].substr(4) + '/style.css');
      }
    });
  }

  /**
   * Dynamically load CSS file
   * @param pathToFile
   */
  function loadCSSFile(pathToFile) {
    // if given CSS file is not loaded yet
    if ($.inArray(pathToFile, loadedTemplatesCSSFiles)<0) {
      log('Loading CSS file', pathToFile);
      $('<link>')
        .appendTo('head')
        .attr({
          type: 'text/css',
          rel: 'stylesheet',
          href: pathToFile
        });
      loadedTemplatesCSSFiles.push(pathToFile);
    }
  }


  /**
   * Tablesort callback for parsing cell values
   * @param $th
   * @param $td
   * @param $tablesort
   * @returns {*}
   */
  function tablesortGetValue($th, $td, $tablesort) {
    if ($th.hasClass(code+'-Int') || $th.hasClass(code+'-Decimal') || $th.hasClass(code+'-Percent')) {
      return parseFloat($td.text().replace(/[^0-9.-]/g,''));
    } else if ($th.hasClass(code+'-BigNumber')) {
      var map = {K: 1000, M: 1000000, B: 1000000000};
      var bigNumber = $td.text();
      var symbol = bigNumber.substr(-1);
      if (map.hasOwnProperty(symbol)) {
        var number = parseFloat(bigNumber.replace(/[^0-9.]/g,'')) * map[symbol];
      } else {
        var number = parseFloat(bigNumber.replace(/[^0-9.]/g,''));
      }
      return number;
    } else {
      return $td.text();
    }
  }

  /**
   * Check whether user has IE browser
   * @returns {boolean}
   */
  function checkIE() {
    var ua = window.navigator.userAgent;

    // IE, Edge etc
    if (ua.indexOf('MSIE ') > 0 || ua.indexOf('Trident/') > 0 || ua.indexOf('Edge/') > 0)
      return true;

    // other browser
    return false;
  }

  function log() {
    if (smwGlobals.debug!=0) {
      console.log('PSMW', arguments);
    }
  }

  return {
    init: initializeWidgets,
    getMarketData: function() {return marketData},
    getWidgetsFields: getWidgetsFields,
    tablesortGetValue: tablesortGetValue,
    retrieveMarketData: retrieveMarketData,
    checkIE: checkIE,
    log: log
  };
})(jQuery);

if (typeof Number.prototype.formatNumber === 'undefined') {
  Number.prototype.formatNumber = function() {
    var decimalDigits = typeof arguments[0] != 'undefined' ? arguments[0] : 2;
    var locale = typeof arguments[1] != 'undefined' ? arguments[1] : 'en-US';

    return this.toLocaleString(locale, {
      minimumFractionDigits: decimalDigits,
      maximumFractionDigits: decimalDigits
    });
  }
}