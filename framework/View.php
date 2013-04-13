<?php

/**
 * The base class of all Controllers
 *
 * @author Young Suk Ahn
 */
abstract class View {

	protected $view_name;
	protected $resource_paths;

	private static $_global_model = array();
	
	/**
	 * The constructure initializes the template engine.
	 */
  public function __construct() {
    $this->resource_paths = array(VIEWS_PATH);
  }
    
  public function add_path($template_path) {
    array_unshift($this->resource_paths, $template_path);
  }

	/**
	 * Sets the name of the view
	 */
	public abstract function set_name($view_name);

	/**
	 * Sets the model to be used
	 */
	public abstract function set_model($key, $value);

	/**
	 * Renders
	 */
	public abstract function render();
	
  
	/**
	 * Factory method to create view objects
   *
   * @param string $view_name     The name of the view (notice, view name is usually template name w/o the extension)
   * @param string $template_path The path where the templates are. If non  is provided, searches in the common view path.
	 */
	public static function create($view_name, $template_path = null, $view_class_name = VIEW_CLASS)
	{
		require_once FRAMEWORK_PATH . $view_class_name . '.php';
		$view = new $view_class_name;
		$view->set_name($view_name);
    if (!empty($template_path)) {
      $view->add_path($template_path);
    }
		return $view;
	}
	
	public static function set_global($key, $value)
	{
		View::$_global_model[$key] = $value;
	}
	
	public static function get_globals()
	{
		return View::$_global_model;
	}
  
}
