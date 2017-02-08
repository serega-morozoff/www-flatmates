<?php

/**
 * @file
 * Contains PlacesApiAutocompleteCacheServiceInterface.
 */

/**
 * Google Places API Interface for the Cache service.
 */
interface PlacesApiAutocompleteCacheServiceInterface {

  /**
   * Get the data from the cache for the provided cid.
   *
   * @param string $cid
   *   The cache id.
   *
   * @return mixed
   *   The cached data.
   */
  public function get($cid);

  /**
   * Save the data to cache.
   *
   * @param string $cid
   *   The cache id.
   * @param mixed $data
   *   The data to be cached.
   */
  public function set($cid, $data);

  /**
   * Clears all the records from the cache.
   */
  public function clear();

}
