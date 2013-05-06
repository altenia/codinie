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
 */
class ControllerBase
{
  protected $view; // The View template (usually the layout view)
  protected $auto_render = false; // Whether or not auto-render the view
  
  protected $request_context; // The request context
  
  protected $start_time;     // Timestamp just before executing the action 
    
  /**
   * The default constructor.
   */
  public function __construct() {
  }
    
  /**
   * Renders the view.
   * 
   * @return none  
   */
  public function render_view() {
    if (!empty($this->view)) {
	  View::set_shared_data('context_path', $this->request_context->context_path);
      View::set_shared_data('request_context', $this->request_context);
      $this->view->render();
    }
  }

  /**
   * Initializes the controller by setting the request context
   * 
   * @param RequestContext $request_context The request context
   * 
   * @return none
   */
  public function init($request_context) {
    $this->request_context = $request_context;
  }

  /**
   * Called by the framework before calling the actual action method
   *
   * @return none
   */
  public function pre_action() {
    // Default behavior is to just stamp the start time.
    $this->start_time = microtime(true);
  }

  /**
   * Called by the framework after calling the actual action method.
   * Rendes if auto_render is set to true, and also 
   *
   * @return none
   */
  public function post_action() {
    
    if ($this->auto_render) {
      $this->render_view();
    }
    $elapsed_time_ms = round(microtime(true) - $this->start_time, 3) * 1000;
    if ($elapsed_time_ms > 200) {
      // @todo: Log a warning saysing that the execution time exceeded some threshold 
    }
  }

  /**
   * Creates a view from the module's view subdirectory
   *
   * @param string $view_name The name of the view to create. The view name usually 
   *                   maps to a template file in the path specified in the configuration.
   *
   * @return none
   */
  public function create_view($view_name, $is_shared = false) {
    $view_path = null;
    if ($is_shared) {
      $view_path = VIEWS_PATH;
    } else {
      $view_path = $this->get_module_path() . 'views' . DIRECTORY_SEPARATOR;
    }
    return View::create($view_name, null, $view_path);
  }

  /**
   * Gets the module name.
   *
   * @return string The current module name
   */
  public function get_module_name() {
    return $this->request_context->module_name;
  }
    
  /**
   * Gets the controller name
   *
   * @return string The current controller name
   */
  public function get_controller_name() {
    return $this->request_context->controller_name;
  }

  /**
   * Gets the action name
   *
   * @return string The current action name
   *
   */
  public function get_action_name() {
    return $this->request_context->action_name;
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
   *
   * @return boolean True is the HTTP method is GET, false otherwise
   */
  public function is_method_get() {
    return $this->request_context->request_method == 'GET';
  }

  /**
   * Returns true if the current HTTP method is POST
   *
   * @return boolean True is the HTTP method is POST, false otherwise
   */
  public function is_method_post() {
    return $this->request_context->request_method == 'POST';
  }

  /**
   * Returns true if the current HTTP method is PUT
   *
   * @return boolean True is the HTTP method is PUT, false otherwise
   */
  public function is_method_put() {
    return $this->request_context->request_method == 'PUT';
  }

  /**
   * Returns true if the current HTTP method is DELETE
   *
   * @return boolean True is the HTTP method is DELETE, false otherwise
   */
  public function is_method_delete() {
    return $this->request_context->request_method == 'DELETE';
  }

  /**
   * Redirects to the corresponding URL
   * 
   * @param string $url The URL to redirect to
   * 
   * @return none
   */
  public function redirect($url) {
    // @todo timestamp before redirecting
    header('Location: ' . $url);
    exit();
  }

  /**
   * Returns the file path of the current module
   *
   * @return string The current module's physical path
   */
  public function get_module_path() {
    return Dispatcher::instance()->module_path . $this->get_module_name() . DIRECTORY_SEPARATOR;;
  }
}
