<?php
/**
 * This file contains the implementation of the RequestContext class and Dispatcher singleton class.
 * The dispatcher is the main entry point of the framework.
 *
 * PHP version 5
 * 
 * @author    Young Suk Ahn <ysahn@altenia.com>
 */

/**
 * This class holds information about the request, including module, controller action names and other HTTP request
 * specific informations.
 * The information in this class is valid through the life of a request.
 *
 */
class RequestContext
{
  public $request_uri;    // from $_SERVER['REQUEST_URI']
  public $path_info;      // from $_SERVER['PATH_INFO']
  public $request_method; // from $_SERVER['REQUEST_METHOD']: GET, POST, PUT, DELETE
  public $context_path;   // the path just before the script name. e.g: site.com/system/module/index.php => system/module/

  public $module_name;    // The name of the module
  public $controller_name;// The name of the controller
  public $action_name;    // The name of the action

  /**
   * Any additional user defined attributes.
   * The attributes is useful for sending user variables when calling another
   * action (nested action call). Remember that it is not possible to set the
   * request parameters (GET/POST)
   */
  public $atttributes = array();
}

/**
 * The Dispatcher class encapsulates the dispatching process.
 * The dispatching is the main entry point for the application, and consist of
 * retrieving the controller and action names from Request information and
 * instantiating a controller class and executing the proper action method.
 */
class Dispatcher
{

  private static $_instance = null; // Singleton instance

  public $use_path_info = true; // Whether or not to use path info to extract the controller and action names.

  public $default_controller_name = 'Home';
  public $default_action_name = 'index';

  public $controller_qparam = '_c'; // The query parameter's key to obtain the controller name
  public $action_qparam = '_a';     // The query parameter's key to obtain the action name
  public $action_method_prefix = 'action_';
  
  public $module_path = MODULES_PATH;
  public $script_name = '/index.php'; // The php script (file) name that serves as the entry point.

  const CONTROLLER_PREFIX = 'Controller_'; // String appended to the controller name to build the php filename
  
  /**
   * Private constructor to avoid direct instantiation
   */
  private function __construct() {
  }
  
  /**
   * Returns the singleton instance of the dispatcher
   *
   * @return The singleton instance of the dispatcher.
   */
  public static function instance() {
    if (self::$_instance === null) {
        self::$_instance = new Dispatcher();
    }
    return self::$_instance;
  }

  /**
   * Extracts the controller and action name, instantiates the controller and calls the action of the controller.
   * There are two ways (configurable) to obtain controller and action names: (1) from PATH_INFO, e.g.
   * www.site.com/index.php/<controller_name>/<action_name>, or from the regular query string e.g.
   * www.site.com/index.php?_c=User_Admin
   *
   * @param string $controller_name The controller name. If null is passed, the function retrieves from the URL
   * @param string $action_name     The action name. If null is passed, the function retrieves from the URL
   *
   * @return none
   */
  public function dispatch($controller_name = null, $action_name = null) {

   $request_context = $this->_create_request_context();
    if ($this->use_path_info) { // Obtains the controller and action names from the path information
      // The URL is parsed as www.site.com/index.php/<controller_name>/<action_name>
      if ($request_context->path_info != null) {
        // Parse parseInfo to obtain controller and action names
        $tokens = explode('/', $request_context->path_info);
        if (empty($controller_name) && sizeof($tokens) > 1) {
          $controller_name = $tokens[1];
          if (empty($action_name) && sizeof($tokens) > 2) {
              $action_name = $tokens[2];
          }
        }
      }
    } else { // Obtains the controller and action names from the query string (GET params)
      // @todo: Use the wfparams. Because of the dependency, I will have to use global_store_header or global_joss_header (dependencies such as CSNSESSOID??)
      //        Which means that I am tied to specific site...
      //        Later I will figure out how I can use the wfrequest including just essential libraries
      $_temp_controller_name = array_key_exists($this->controller_qparam, $_GET) ? $_GET[$this->controller_qparam] : null;
      if (empty($controller_name) && !empty($_temp_controller_name)) {
        $controller_name = $_temp_controller_name;
      }
      $_temp_action_name = array_key_exists($this->action_qparam, $_GET) ? $_GET[$this->action_qparam] : null;
      if (empty($action_name) && !empty($_temp_controller_name)) {
        $action_name = $_temp_action_name;
      }
    }
    
    // If still empty, populate with default values
    if (empty($controller_name)) {
      $controller_name = $this->default_controller_name;
    }
    if (empty($action_name)) {
      $action_name = $this->default_action_name;
    }

    // Extract the module name (the substring in the controller name that precedes the underscore)
    // E.g. in the URL www.site.com/index.php?_c=User_Admin
    // The module name would be User (from the User_Admin)
    $module_name = '';
    $underscore_pos = strpos($controller_name, '_');
    if ($underscore_pos > 0) {
      $module_name = strtolower(substr($controller_name, 0, $underscore_pos));
    }

    $request_context->module_name = $module_name;
    $request_context->controller_name = $controller_name;
    $request_context->action_name = $action_name;

    //
    $this->dispatch_internal($module_name, $controller_name, $action_name, $request_context);
  }

  /**
   * This method does the actual instantiation of the controller and
   * invocation of the action method
   *
   * @param string $module_name
   * @param string $controller_name
   * @param string $action_name
   * @param string $request_context
   */
  public function dispatch_internal($module_name, $controller_name, $action_name, $request_context) {
    $module_folder = '';
    if (!empty($module_name)) {
      $module_folder = $module_name . DIRECTORY_SEPARATOR;
    }

    // Dynamically instantiates and excutes the action from the controller
    $controller_class_name = self::CONTROLLER_PREFIX . $controller_name;

    // @todo: refactor to take out the instantiation and actual action invocation in
    //        another method. I will increase readability and allow for nested dispatch.
    $controller_filename = $this->module_path . $module_folder . $controller_class_name . '.php';
    if (file_exists($controller_filename)) {
      include_once $controller_filename;

      $controller = new $controller_class_name();
      
      $request_context->module_name = $module_name;
      $request_context->controller_name = $controller_name;
      $request_context->action_name = $action_name;
      $controller->init($request_context);

      // Invoke the action of the instantiated controller
      // @todo: add logging for debugging
      $action_method_name = $this->action_method_prefix . $action_name;
      if (method_exists($controller, $action_method_name)) {
        // Controller's pre_action() is called prior to the actual action
        // Common pre processing logic can be factored in this method.
        $controller->pre_action();

        // The actual action method call.
        $controller->$action_method_name();

        // The post processming method.
        $controller->post_action();
      } else {
        // @todo: Use logger to log error.
        // @todo: Instead of die-ing, better to 404 (Page not found)
        die('Controller [' . $controller_class_name . '] does not contain action method [' . $action_method_name . '].');
      }
    } else {
      // @todo: Use logger to log error.
      // @todo: Instead of die-ing, better to 404 (Page not found)
      die('Controller [' . $controller_class_name . '] not found.');
    }
  }

  /**
   * Creates the request context instance and populates the fields from the current request information.
   *
   * @return RequestContext The initialized request context
   */
  private function _create_request_context() {
    $request_context = new RequestContext();
    $request_context->request_uri = $_SERVER['REQUEST_URI'];
    if (array_key_exists('PATH_INFO', $_SERVER)) {
      // Contains any client-provided pathname information trailing the actual script filename but preceding the query string, if available
      // URL http://www.example.com/php/path_info.php/some/stuff?foo=bar,
      // path_info would contain /some/stuff.
      $request_context->path_info = $_SERVER['PATH_INFO'];
    }
    $request_context->request_method = $_SERVER['REQUEST_METHOD'];

    $request_context->context_path = str_replace($this->script_name, '', $_SERVER['SCRIPT_NAME']);

    return $request_context;
  }

}
