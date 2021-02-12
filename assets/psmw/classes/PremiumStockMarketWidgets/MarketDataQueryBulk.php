<?php

namespace PremiumStockMarketWidgets;

abstract class MarketDataQueryBulk {
  const YQL_URL = 'https://query.yahooapis.com/v1/public/yql?q=%s&env=store://datatables.org/alltableswithkeys&format=json';

  // protected properties to be initialized in child classes
  protected $maxCacheTime;
  protected $type;
  protected $dataUrl;
  protected $resultPropertyPath;

  private $env;
  private $assets;
  private $dataFolder;
  private $symbols = array();
  private $arguments = array();
  private $queryParams = array();
  private $data;

  public function __construct(array $arguments) {
    $this->env = Config::get();
    $this->assets = Asset::get();
    $this->dataFolder = SMW_ROOT_DIR . '/data';
    $this->arguments = $arguments;
    $this->symbols = isset($arguments['symbols']) ? $arguments['symbols'] : [];
    $this->queryParams = isset($arguments['queryParams']) ? $arguments['queryParams'] : [];
    $this->data = new \stdClass();
    $this->initialize($this->env);
    return $this;
  }

  /**
   * This function should be called in the constructir and set $type, $dataUrl, $maxCacheTime, $resultPropertyPath class properties
   * @return mixed
   */
  abstract protected function initialize($env);

  abstract protected function format($rawData, $arguments, $env);

  private function retrieveData() {
    $now = time();
    $symbolsPullFromApi = [];

    foreach ($this->symbols as $symbol) {
      $cacheFileName = $this->cacheFileName($symbol);
      if (file_exists($cacheFileName) && ($now - filemtime($cacheFileName) < $this->maxCacheTime)) {
        Helper::log(sprintf('Reading data for %s from cache', $symbol));
        $this->data->$symbol = json_decode(file_get_contents($cacheFileName));
      } else {
        $symbolsPullFromApi[] = $symbol;
      }
    }

    if (!empty($symbolsPullFromApi)) {
      $url = vsprintf($this->dataUrl, array_merge([implode(',', $symbolsPullFromApi)], $this->queryParams));
      Helper::log(sprintf('Getting data for %s (%s) from server %s', implode(',', $symbolsPullFromApi), implode(',', $this->queryParams), $url));
      if ($this->env->useYql) {
        $url = sprintf(self::YQL_URL, urlencode(sprintf('SELECT * FROM json WHERE url="%s"', $url)));
      }
      $http = new HttpClient($url);
      $http->get();

      if ($http->statusOk()) {
        if ($this->env->useYql) {
          // TODO: implement this
        } else {
          $responseBody = Helper::cleanString($http->getBody());
          if ($responseJson = json_decode($responseBody)) {
            $responseResultObjects = Helper::getObjectProperty($responseJson, $this->resultPropertyPath);
            if (is_array($responseResultObjects)) {
              foreach ($responseResultObjects as $responseResultObject) {
                if (isset($responseResultObject->symbol)) {
                  $symbol = $responseResultObject->symbol;
                  $this->data->$symbol = new \stdClass();
                  $this->data->$symbol->{$this->type} = $responseResultObject;
                  // set virtual (calculated) fields
                  $this->data->$symbol->virtual = new \stdClass();
                  $this->data->$symbol->virtual->symbol = $symbol;
                  $this->data->$symbol->virtual->name =
                    isset($this->env->companies->$symbol) ?
                    $this->env->companies->$symbol :
                      (isset($responseResultObject->shortName) ?
                      $responseResultObject->shortName :
                        (isset($this->assets->$symbol) ?
                        $this->assets->$symbol :
                        $symbol));
                  $this->data->$symbol->virtual->lastUpdated = time();
                  // create cache folder
                  if (!file_exists($this->dataFolder)) {
                    mkdir($this->dataFolder, 0755, TRUE);
                  }
                  // save data to cache
                  if (!file_put_contents($this->cacheFileName($symbol), json_encode($this->data->$symbol))) {
                    Helper::log(sprintf('Failed to save data to %s', $this->cacheFileName($symbol)));
                  }
                } else {
                  Helper::log('Symbol is not found in the data object');
                  Helper::log($responseResultObject);
                }
              }
            } else {
              Helper::log('Failed to retrieve data');
              Helper::log($responseResultObjects);
            }
          } else {
            Helper::log(sprintf('Failed to JSON decode data: %d / %s', json_last_error(), json_last_error_msg()));
            Helper::log($responseBody);
          }
        }
      } else {
        Helper::log(sprintf('Error HTTP response: %s|%s', $http->status()->number, $http->getBody()));
      }
    }
  }

  /**
   * Get raw unformatted market data
   * @return \stdClass
   */
  private function rawData() {
    if (!get_object_vars($this->data))
      $this->retrieveData();

    return $this->data;
  }

  public function data() {
    return $this->format($this->rawData(), $this->arguments, $this->env);
  }

  private function cacheFileName($symbol) {
    return $this->dataFolder . '/' . $symbol . '_' . $this->type . (!empty($this->queryParams) ? '_' . implode('_', $this->queryParams) : '') . '.json';
  }
}