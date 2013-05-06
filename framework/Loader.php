<?php
/**
 * This file contains the implementation of the Loader class.
 *
 * @author Young Suk Ahn <ysahn@altenia.com>
 */

/**
 * The Loader class contains static functions to load php libraries (files)
 */
class Loader
{
  const LOC_LIB       = 0; // Loads from the framework directory
  const LOC_FRAMEWORK = 1; // Loads from the framework directory
  const LOC_SYSTEM    = 2; // Loads a class from the system wide include (php/include/classes)
  const LOC_SITE      = 3; // Loads from the current site’s include (php/<site>/app_includes/classes)

  const CLASS_EXT = '.class.php';

  // The alias to path mapping array
  private static $_location_map = array(
      Loader::LOC_LIB       => LIB_PATH, 
      Loader::LOC_FRAMEWORK => FRAMEWORK_PATH, 
	  Loader::LOC_SYSTEM    => SYSTEM_CLASSES_PATH, 
      Loader::LOC_SITE      => SITE_CLASSES_PATH
    );
  
  /**
   * Returns the path given the location alias
   * 
   * @param string $location The location id 
   * 
   * @return string The actual path
   */
  public static function get_path($location) {
    return self::$_location_map[$location];
  }

  /**
   * Registers a location mapping entry
   * 
   * @param string $location    The location id
   * @param string $actual_path The actual path associated to the location id
   * 
   * @return none
   */
  public static function put_path($location, $actual_path) {
    self::$_location_map[$location] = $actual_path;
  }

  /**
   * Loads one or multiple php libraries (files)
   * 
   * @param string|array $lib_names The library (file) name (or list of class names) to load within the specified location
   * @param string|int   $location  The location to look for the said class file
   *
   * @return none
   */
  public static function load($lib_names, $location = Loader::LOC_SITE) {
    if (empty($lib_names)) {
      return;
    }
    $lib_name_arr = null;
    if (!is_array($lib_names)) {
      $lib_name_arr = array($lib_names);
    } else {
      $lib_name_arr = $class_names;
    }

    $lib_path = null;
    if ( array_key_exists($location, self::$_location_map)) {
      $lib_path = self::$_location_map[$location];
    } else {
      $lib_path = $location;
    }
    
    foreach ($lib_name_arr as $lib_name) {
      if (substr_compare($lib_name, '.php', -strlen('.php'), strlen('.php')) !== 0) {
        $lib_name .= Loader::CLASS_EXT;
      }
      // Adds the last directory separator if necessary
      if (substr($lib_path, -1) != DIRECTORY_SEPARATOR) {
        $lib_path .= DIRECTORY_SEPARATOR;
      }
      include_once $lib_path . $lib_name;
    }
  }
}
