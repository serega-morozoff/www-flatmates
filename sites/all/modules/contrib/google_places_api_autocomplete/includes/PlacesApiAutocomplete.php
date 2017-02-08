<?php

/**
 * @file
 * Contains PlacesApiAutocomplete.
 */

/**
 * Queries the Google Places API for autocomplete suggestions.
 */
class PlacesApiAutocomplete {

  /**
   * The input from the user.
   *
   * @var string
   */
  protected $input;

  /**
   * The Google API key.
   *
   * @var string
   */
  protected $key;

  /**
   * The Google API endpoint.
   *
   * @var string
   */
  protected $endPoint = 'https://maps.googleapis.com/maps/api/place/autocomplete/json?';

  /**
   * The options (parameters) for the request to the Places API.
   *
   * @var array
   */
  protected $options = array();

  /**
   * Static cache for the requests.
   *
   * @var array
   */
  protected static $staticCache = array();

  /**
   * A cache service.
   *
   * @var PlacesApiAutocompleteCacheServiceInterface
   */
  protected $cacheService;

  /**
   * The results of the query.
   *
   * @var array
   */
  protected $results;

  /**
   * Constructor for PlacesApiAutocomplete.
   *
   * @param string $key
   *   The Google API key.
   * @param PlacesApiAutocompleteCacheServiceInterface $cache_service
   *   A cache object, for local caching.
   */
  public function __construct($key, PlacesApiAutocompleteCacheServiceInterface $cache_service = NULL, $options = array()) {
    if (is_object($cache_service) && !($cache_service instanceof PlacesApiAutocompleteCacheServiceInterface)) {
      throw new Exception("Cache service must implement interface PlacesApiAutocompleteCacheServiceInterface", 1);
    }
    $this->setKey($key);

    $this->setOptions($options);
    $this->setCacheService($cache_service);
  }

  /**
   * Performs the search on the Places API.
   *
   * @param string $string
   *   The query string.
   *
   * @return array
   *   The results of the search.
   */
  public function search($string) {
    $this->setInput($string);
    $this->executeSearch();
    return $this->getResults();
  }

  /**
   * Constructs a cache id based the input.
   *
   * @return string
   *   The cid
   */
  protected function getCid() {
    return 'hash-' . md5($this->getInput());
  }

  /**
   * Retrieves the results from cache.
   *
   * @return array
   *   The cached results, or NULL.
   */
  protected function cacheGet() {
    // Get the cid from the current input.
    $cid = $this->getCid();

    // First, try to find the cid in the static cache.
    if (isset(self::$staticCache[$cid])) {
      return self::$staticCache[$cid];
    }

    // Alternativly try to use the cache service we received at construction.
    if ($cache_service = $this->gertCacheService()) {
      $cache = $cache_service->get($cid);

      // If we found a cache value, validate the input is the same (to prevent
      // an hypotetical situation where 2 input strings have the same hash).
      if (isset($cache) && !empty($cache) && $cache->input === $this->getInput()) {
        // Save the data in the static cache and return it.
        return self::$staticCache[$cid] = $cache->data;
      }
    }
  }

  /**
   * Stores a value in the cache.
   *
   * @param mixed $data
   *   The value to be stored.
   */
  protected function cacheSet($data) {
    $cid = $this->getCid();

    if ($cache_service = $this->gertCacheService()) {
      $cache = new StdClass();
      $cache->data = $data;
      // Add the input to the cache object, this allows validation on it at
      // retrieval.
      $cache->input = $this->getInput();
      $cache_service->set($cid, $cache);
    }
  }

  /**
   * Getter for the API key.
   *
   * @return string
   *   The API key.
   */
  public function getKey() {
    return $this->key;
  }

  /**
   * Setter for the API key.
   *
   * @param string $key
   *   The API key.
   */
  public function setkey($key) {
    $this->key = $key;
  }

  /**
   * Getter for the options.
   *
   * @return array
   *   The options.
   */
  protected function getOptions() {
    return $this->options;
  }

  /**
   * Setter for the options.
   *
   * @param array $options
   *   The options.
   */
  protected function setOptions(array $options) {
    $this->options = $options;
  }

  /**
   * Getter for the cache service.
   *
   * @return PlacesApiAutocompleteCacheServiceInterface
   *   The cache service.
   */
  protected function gertCacheService() {
    return $this->cacheService;
  }

  /**
   * Setter for the cache service.
   *
   * @param PlacesApiAutocompleteCacheServiceInterface $cache_service
   *   The cache service.
   */
  protected function setCacheService(PlacesApiAutocompleteCacheServiceInterface $cache_service) {
    $this->cacheService = $cache_service;
  }

  /**
   * Getter for the input.
   *
   * @return string
   *   The input.
   */
  public function getInput() {
    return $this->input;
  }

  /**
   * Setter for the input.
   *
   * @param string $string
   *   The input.
   */
  public function setInput($string) {
    $this->input = $string;
  }

  /**
   * Getter for the endpoint.
   *
   * @return string
   *   The endpoint.
   */
  public function getEndPoint() {
    return $this->endPoint;
  }

  /**
   * Setter for the endpoint.
   *
   * @param string $end_point
   *   The endpoint.
   */
  public function setEndPoint($end_point) {
    $this->endPoint = $end_point;
  }

  /**
   * Getter for the results.
   *
   * @return array
   *   The results.
   */
  public function getResults() {
    return $this->results;
  }

  /**
   * Setter for the results.
   *
   * @param array $results
   *   The results.
   */
  public function setResults(array $results) {
    $this->results = $results;
  }

  /**
   * Executes the actual request to the Places API end point.
   */
  protected function executeSearch() {
    // First, attempt to get the data from cache. If this fails, we will query
    // the Places API.
    if (!$data = $this->cacheGet()) {
      // Get cURL resource.
      $curl = curl_init();

      // Set some options.
      curl_setopt_array($curl, array(
          CURLOPT_RETURNTRANSFER => 1,
          CURLOPT_URL => $this->prepareUrl(),
      ));

      // Send the request.
      $response = curl_exec($curl);

      // Close request to clear up some resources.
      curl_close($curl);

      // Decode the response json.
      $decoded_response = json_decode($response);
      if (!is_object($decoded_response) || $decoded_response->status !== 'OK') {
        // If not an object or the status is not OK, we hit some error.
        throw new Exception("Error getting data from Google Places API", 1);
      }

      // Get just the predictions from the response.
      $data = $decoded_response->predictions;

      // Save these to cache for future requests.
      $this->cacheSet($data);
    }

    // Set the results.
    $this->setResults($data);
  }

  /**
   * Constructs the url for the request.
   *
   * @return string
   *   The url.
   */
  protected function prepareUrl() {
    // Get the options for the request.
    $options = $this->getOptions();

    // Add the key and the input to them.
    $parameters = array(
      'key' => $this->getKey(),
      'input' => $this->getInput(),
    ) + $options;

    // Url encode the parameters.
    $processed_parameters = array();
    foreach ($parameters as $name => $value) {
      $processed_parameters[] = urlencode($name) . '=' . urlencode($value);
    }

    // Add the parameters to the endpoint and return them.
    return $this->getEndPoint() . implode('&', $processed_parameters);
  }

}
