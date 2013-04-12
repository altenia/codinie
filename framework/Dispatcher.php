<?php

/**
 * The base class of all Controllers
 *
 * @author Young Suk Ahn
 */
class Dispatcher {

  static $_inst = null; // Singleton instance
  
  public $use_path_info = true; // Whether or not to use path info to extract the controller and action names.

  public $default_controller_name = 'Home';
  public $default_action_name = 'index';
  
  public $controller_qparam = '_c';
  public $action_qparam = '_a';
  
  public $module_path = MODULES_PATH;
  
  const CONTROLLER_PREFIX = 'Controller_'; // String appended to the controller name to build the php filename
  
  /**
   * Private constructor to avoid direct instantiation
   */
  private function __construct() {
  }
  
  /**
   * Returns the singleton instance of the dispatcher
   *
   * @return 
   */
  public static function instance()
  {
    if (self::$_inst === null) {
        self::$_inst = new Dispatcher();
    }
    return self::$_inst;
  }

	/**
	 * Extracts the controller and action name, instantiates the controller and calls the action of the controller.
   *
   * @param string $controller_name The controller name. if null is passed, the function retrieves from the URL
   * @param string $action_name     The action name. if null is passed, the function retrieves from the URL
   * 
   * @return none
	 */
	public function dispatch($controller_name = null, $action_name = null)
	{
    
    if($this->use_path_info) {
      // Obtains the controller and action names from the path information
      $pathInfo = null;
      if ( array_key_exists('PATH_INFO', $_SERVER) )
          $pathInfo = $_SERVER['PATH_INFO'];

      // The URL is parsed as <URL_ROOT>/index.php/<controller_name>/<action_name>
      if ($pathInfo != null) {
          // Parse parseInfo to obtain controller and action names
          $tokens = explode("/", $pathInfo);
          if (empty($controller_name) && sizeof($tokens) > 1) {
              $controller_name = $tokens[1];
              if (empty($action_name) && sizeof($tokens) > 2) {
                  $action_name = $tokens[2];
              }
          }
      }
    } else {
      // Obtains the controller and action names from the query string (GET params)
      if (empty($controller_name) && isset($_GET[$this->controller_qparam]))
        $controller_name = $_GET[$this->controller_qparam];
      if (empty($action_name) && isset($_GET[$this->action_qparam]))
        $action_name = $_GET[$this->action_qparam];
    }
    
    // If still empty, populate with default values
    if (empty($controller_name)) {
      $controller_name= $this->default_controller_name;
    }
    if (empty($action_name)) {
      $action_name= $this->default_action_name;
    }

    // Extract the module name (the substring in the controller name that precedes the underscore)
    $module_name = '';
    $underscore_pos = strpos($controller_name, '_');
    if ($underscore_pos > 0) {
      $module_name = strtolower(substr($controller_name, 0, $underscore_pos));
    }
	$module_folder = '';
    if (!empty($module_name)) {
      $module_folder = $module_name . DIRECTORY_SEPARATOR;
    }
    
    // Dynamically instantiates and excutes the action from the controller
    $controller_class_name = self::CONTROLLER_PREFIX . $controller_name;
    
    $controller_filename = $this->module_path . $module_folder . $controller_class_name . '.php';
    if (file_exists($controller_filename)) {
      require_once $controller_filename;

      $controller = new $controller_class_name();
      $controller->set_module_name($module_name);
      $controller->set_controller_name($controller_name);
      $controller->set_action_name($action_name);
      $controller->init();

      // Invoke the action of the instantiated controller
	  // @todo: add logging for debugging
      if (method_exists($controller, $action_name)) {
        $controller->pre_action();
        $controller->$action_name();
        $controller->post_action();
      } else {
        die('Controller [' . $controller_class_name . '] does not include action [' . $action_name . '].');
      }
    } else {
      die('Controller [' . $controller_filename . '] not found.');
    }
	}
	
}
