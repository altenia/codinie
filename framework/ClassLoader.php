<?php
/**
 * The base class of all Controllers
 *
 * @author Young Suk Ahn
 */
class Loader {
	
  const LOC_FRAMEWORK = 0; // Loads from the framework directory
  const LOC_SYSTEM    = 1; // Loads a class from the system wide include (php/include/classes)
  const LOC_SITE      = 2; // Loads from the current site’s include (php/<site>/app_includes/classes)
  const LOC_MODULE    = 3; // Loads from the current module’s

  const CLASS_EXT = '.class.php';
  const MODULES_FOLDER = 'modules/';

  private static $_location_map = array(
      Loader::LOC_FRAMEWORK => FRAMEWORK_PATH, 
	  Loader::LOC_SYSTEM => CLASSES_PATH, 
      Loader::LOC_SITE => CLASSES_PATH, 
    );
  
  /**
   * Puts a class path alias to the classpath location mapping table
   */
  public function put_classpath_alias($alias, $actual_path) {
    $_location_map[$alias] = $actual_path;
  }
  
  /**
   * Loads one or multiple class files
   * 
   * @param string|array $class_names
   * @param string|int   $location    the location to look for the said class file
   *
   */
	public static function load($class_names, $location = Loader::LOC_SYSTEM )
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
