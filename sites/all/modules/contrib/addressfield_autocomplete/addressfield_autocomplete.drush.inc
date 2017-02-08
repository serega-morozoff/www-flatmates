<?php

/**
 * @file
 * Drush integration for addressfield autocomplete.
 */

define('ADDRESSFIELD_AUTOCOMPLETE_GEOCOMPLETE_DOWNLOAD_URI', 'https://github.com/ubilabs/geocomplete/archive/');

/**
 * Implements hook_drush_command().
 */
function addressfield_autocomplete_drush_command() {
  $items = array();
  $items['geocomplete-plugin'] = array(
    'callback' => 'drush_addressfield_autocomplete_geocomplete_plugin',
    'description' => dt('Download and install the Geocomplete plugin.'),
    'bootstrap' => DRUSH_BOOTSTRAP_DRUSH,
    'options' => array(
      'version' => dt('The version of the geocomplete plugin to download e.g. 1.6.3'),
      'path' => dt('A path where to install the Geocomplete plugin. If omitted Drush will use the default location.'),
    ),
    'aliases' => array('geocompleteplugin', 'addressfieldautocomplete-plugin'),
  );

  return $items;
}

/**
 * Implements hook_drush_help().
 */
function addressfield_autocomplete_drush_help($section) {
  switch ($section) {
    case 'drush:geocomplete-plugin':
      return dt('Download and install the Geocomplete plugin from ubilabs.github.io/geocomplete/, default location is sites/all/libraries.');
  }
}

/**
 * Command to download the Geocomplete plugin.
 */
function drush_addressfield_autocomplete_geocomplete_plugin() {
  $version = drush_get_option('version');
  $path = drush_get_option('path', 'sites/all/libraries');
  // Make sure version matches a specific regex
  if (!preg_match('/v?(\d\.?){2,3}/', $version)) {
    $version = 'master';
  }

  $olddir = getcwd();
  try {
    _addressfield_autocomplete_drush_plugin_process($version, $path);
    drush_log(dt('Geocomplete plugin has been installed in @path', array('@path' => $path)), 'success');
  } catch (\Exception $e) {
    drush_log(dt($e->getMessage()), 'error');
  }
  // Set working directory back to the previous working directory.
  chdir($olddir);
}

/**
 * A helper function to process the download of the geocomplete plugin.
 *
 * @param string $version
 * @param string $path
 */
function _addressfield_autocomplete_drush_plugin_process($version, $path) {
  // Create the path if it does not exist.
  if (!is_dir($path)) {
    if (!drush_op('mkdir', $path)) {
      throw new \Exception(sprintf('Directory %s can not be created', $path));
    }
    drush_log(dt('Directory @path was created', array('@path' => $path)), 'notice');
  }

  // Make sure path is writable
  if (!is_writable($path)) {
    throw new \Exception(sprintf('%s is not writable', $path));
  }

  // Set the directory to the download location.
  chdir($path);

  // Try normal path
  $filepath = _addressfield_autocomplete_drush_plugin_download($version);
  if (!$filepath) {
    // Try the path with v prefix
    $filepath = _addressfield_autocomplete_drush_plugin_download('v' . $version);
    if (!$filepath) {
      throw new \Exception('Geocomplete plugin could not be downloaded.');
    }
  }

  // Remove any existing Geocomplete plugin directory.
  if (is_dir('geocomplete')) {
    drush_delete_dir('geocomplete', TRUE);
    drush_log(dt('An existing Geocomplete plugin was deleted from @path', array('@path' => $path)), 'notice');
  }

  // Decompress the zip archive.
  $filename = basename($filepath);
  $listing = drush_tarball_extract($filename, FALSE, TRUE);
  if (!$listing || empty($listing[1])) {
    throw new \Exception('Geocomplete plugin could not be extracted');
  }

  $dirname = rtrim($listing[1], '/');
  // Change the directory name to "geocomplete" if needed.
  if ($dirname != 'geocomplete' && !drush_move_dir($dirname, 'geocomplete', TRUE)) {
    throw new \Exception('Geocomplete plugin could not be moved');
  }
}

/**
 * A helper function to download the plugin.
 *
 * @param string $version
 */
function _addressfield_autocomplete_drush_plugin_download($version) {
  // Download the tarball archive.
  $url = ADDRESSFIELD_AUTOCOMPLETE_GEOCOMPLETE_DOWNLOAD_URI . $version . '.zip';
  return drush_download_file($url);
}
