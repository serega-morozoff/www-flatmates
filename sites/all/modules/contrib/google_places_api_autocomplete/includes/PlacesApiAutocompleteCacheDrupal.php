<?php

/**
 * @file
 * Contains PlacesApiAutocompleteCacheDrupal.
 */

/**
 * A cache service that uses the Drupal caching system.
 */
class PlacesApiAutocompleteCacheDrupal implements PlacesApiAutocompleteCacheServiceInterface {

  /**
   * The cache bin that will be used.
   *
   * @var string.
   */
  protected $bin;

  /**
   * Constructor for PlacesApiAutocompleteCacheDrupal.
   *
   * @param string $bin
   *   The cache bin that will be used to store the data.
   */
  public function __construct($bin = 'cache_places') {
    $this->setBin($bin);
  }

  /**
   * {@inheritdoc}
   */
  public function get($cid) {
    if ($cache = cache_get($cid, $this->getBin())) {
      return $cache->data;
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function set($cid, $data) {
    return cache_set($cid, $data, $this->getBin());
  }

  /**
   * {@inheritdoc}
   */
  public function clear($cid = NULL) {
    return cache_clear_all($cid, $this->getBin());
  }

  /**
   * Setter for the cache bin.
   *
   * @param string $bin
   *   The cache bin.
   */
  protected function setBin($bin) {
    $this->bin = $bin;
  }

  /**
   * Getter for the bin.
   *
   * @return string
   *   The cache bin.
   */
  protected function getBin() {
    return $this->bin;
  }

}
