<?php
require_once 'View.php';
require_once 'ViewHelper.php';

/**
 * The base class of all Controllers
 *
 * @author Young Suk Ahn
 */
class ControllerBase {

    protected $view; // The optional frame (layout) template
    
    protected $requestUri; // from $_SERVER['REQUEST_URI'];
    protected $pathInfo; // from  $_SERVER['PATH_INFO'];
    protected $requestMethod; // from  $_SERVER['REQUEST_METHOD'];
    
    protected $controllerName;
    protected $actionName;
	
	protected $scripts;
	protected $require_js_main = 'main'; // This may be overrided by the child class's constructor
	protected $css;
    
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
			View::set_global('contextPath', $this->contextPath);
			View::set_global('css', $this->css);
			View::set_global('scripts', $this->scripts);
			View::set_global('require_js_main', $this->require_js_main);
			$this->view->render();
		}
    }

	/**
	 * Initializes the controller, populating the member variables with
	 * basic contextual information such as request URI, pathInto, request Method, and context Path
	 */
    public function init() {
        $this->requestUri = $_SERVER['REQUEST_URI'];
		if (array_key_exists('PATH_INFO', $_SERVER)) {
			// Contains any client-provided pathname information trailing the actual script filename but preceding the query string, if available
			// URL http://www.example.com/php/path_info.php/some/stuff?foo=bar, 
			// pathInfo would contain /some/stuff.
			$this->pathInfo = $_SERVER['PATH_INFO'];
		}
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
		// contextPath is what comes just before /index.php
        $this->contextPath = str_replace("/index.php", "", $_SERVER['SCRIPT_NAME']);
    }
	
	/**
	 * Called by framework before calling the actual action method
	 *
	 */
	public function beforeAction()
	{
		// 
	}
	/**
	 * Called by framework after calling the actual action method
	 *
	 */
	public function afterAction()
	{
		// 
	}
    
	/**
	 * Sets the controller name
	 *
	 */
    public function setControllerName($controllerName) {
        $this->controllerName = $controllerName;
    }
	/**
	 * Sets the action name
	 *
	 */
    public function setActionName($actionName) {
        $this->actionName = $actionName;
    }
	
	/**
	 * Retrives the parameter value from GET or POST (in that order)
	 */
	public function getRequestParam($pname, $def_val = null)
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
    public function isMethodGet()
    {
        return $this->requestMethod == "GET";
    }

	/**
	 * Returns true if the current HTTP method is POST
	 */
    public function isMethodPost()
    {
        return $this->requestMethod == "POST";
    }
	
	/**
	 * Redirects to the corresponding URL
	 */
	public function redirect($url)
	{
		header('Location: ' . $url);
	}
	
}
