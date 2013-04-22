<?php
/**
 * The base class of all Controllers
 *
 * @author Young Suk Ahn
 */
class Loader {
	
  const LOC_LIB       = 0; // Loads from the framework directory
  const LOC_FRAMEWORK = 1; // Loads from the framework directory
  const LOC_SYSTEM    = 2; // Loads a class from the system wide include (php/include/classes)
  const LOC_SITE      = 3; // Loads from the current site’s include (php/<site>/app_includes/classes)

  const CLASS_EXT = '.class.php';

  private static $_location_map = array(
      Loader::LOC_LIB       => LIB_PATH, 
      Loader::LOC_FRAMEWORK => FRAMEWORK_PATH, 
	  Loader::LOC_SYSTEM    => SYSTEM_CLASSES_PATH, 
      Loader::LOC_SITE      => SITE_CLASSES_PATH
    );
  
  /**
   * Returns the 
   */
  public static function get_path($location) {
	return self::$_location_map[$location];
  }
  
  /**
   * Puts a mapping entry of location to the path in the table
   */
  public static function put_path($location, $actual_path) {
    self::$_location_map[$location] = $actual_path;
  }
  
  /**
   * Loads one or multiple class files
   * 
   * @param string|array $class_names
   * @param string|int   $location    the location to look for the said class file
   *
   */
	public static function load($class_names, $location = Loader::LOC_SITE )
	{
    if (empty($class_names)) {
      return;
    }
    $class_name_arr = null;
    if (!is_array($class_names)) {
      $class_name_arr = array($class_names);
    } else {
      $class_name_arr = $class_names;
    }

    $class_path = null;
    if ( array_key_exists($location, self::$_location_map)) {
      $class_path = self::$_location_map[$location];
    } else {
      $class_path = $location;
    }
    
    foreach($class_name_arr as $class_name) {
      if (substr_compare($class_name, '.php', -strlen('.php'), strlen('.php')) !== 0) {
        $class_name .= Loader::CLASS_EXT;
      }
      // Adds the last directory separator if necessary
      if (substr($class_path, -1) != DIRECTORY_SEPARATOR) {
        $class_path .= DIRECTORY_SEPARATOR;
      }
      require_once $class_path . $class_name;
    }
	}
}
