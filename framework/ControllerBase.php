<?php
/**
 * This file contains the implementation of the ControllerBase class
 *
 * @author Young Suk Ahn
 */
require_once 'View.php';
require_once 'ViewHelper.php';

/**
 * The base class of all Controllers
 *
 * @author Young Suk Ahn
 */
class ControllerBase {

    protected $view; // The View template (usually the layout view)
    
    protected $request_uri; // from $_SERVER['REQUEST_URI'];
    protected $path_info;   // from $_SERVER['PATH_INFO'];
    protected $request_method; // from $_SERVER['REQUEST_METHOD'];
    
    protected $module_name;
    protected $controller_name;
    protected $action_name;
    
	/**
	 * The constructure initializes the template engine.
	 */
    public function __construct() {
    }
    
	/**
	 * Renders the view.
	 */
    public function renderView()
    {
		if (!empty($this->view)) {
			View::set_global('context_path', $this->context_path);
			$this->view->render();
		}
    }

	/**
	 * Initializes the controller, populating the member variables with
	 * basic contextual information such as request URI, pathInto, request Method, and context Path
	 */
  public function init() {
      $this->request_uri = $_SERVER['REQUEST_URI'];
  if (array_key_exists('PATH_INFO', $_SERVER)) {
    // Contains any client-provided pathname information trailing the actual script filename but preceding the query string, if available
    // URL http://www.example.com/php/path_info.php/some/stuff?foo=bar, 
    // path_info would contain /some/stuff.
    $this->path_info = $_SERVER['PATH_INFO'];
  }
      $this->request_method = $_SERVER['REQUEST_METHOD'];
  // context_path is what comes just before /index.php
      $this->context_path = str_replace("/index.php", "", $_SERVER['SCRIPT_NAME']);
  }
	
	/**
	 * Called by the framework before calling the actual action method
	 *
	 */
	public function pre_action()
	{
		// 
	}
	/**
	 * Called by the framework after calling the actual action method
	 *
	 */
	public function post_action()
	{
		// 
	}
  
  /**
   * Creates a view from the module's view subdirectory
   */
  public function create_view($view_name)
  {
    $local_view_path = $this->get_module_path() . 'views' . DIRECTORY_SEPARATOR;
    return View::create($view_name, $local_view_path);
  }
  
  /**
	 * Sets the controller name
	 *
	 */
    public function set_module_name($module_name) {
        $this->module_name = $module_name;
    }
    
	/**
	 * Sets the controller name
	 *
	 */
    public function set_controller_name($controller_name) {
        $this->controller_name = $controller_name;
    }
	/**
	 * Sets the action name
	 *
	 */
    public function set_action_name($action_name) {
        $this->action_name = $action_name;
    }
	
	/**
	 * Retrives the parameter value from GET or POST (in that order)
	 */
	public function get_request_param($pname, $def_val = null)
	{
		if (array_key_exists($pname, $_GET)) {
			return $_GET[$pname];
		}
		if (array_key_exists($pname, $_POST)) {
			return $_POST[$pname];
		}
		return $def_val;
	}
    
	/**
	 * Returns true if the current HTTP method is GET
	 */
    public function is_method_get()
    {
        return $this->request_method == "GET";
    }

	/**
	 * Returns true if the current HTTP method is POST
	 */
    public function is_method_post()
    {
        return $this->request_method == "POST";
    }
	
	/**
	 * Redirects to the corresponding URL
	 */
	public function redirect($url)
	{
		header('Location: ' . $url);
	}
	
	public function get_module_path()
	{
		return Dispatcher::instance()->module_path . $this->module_name . DIRECTORY_SEPARATOR;;
	}
}
