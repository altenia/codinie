<?php
require_once 'Savant3.php';

/**
 * The base class of all Controllers
 *
 * @author Young Suk Ahn
 */
abstract class View {

	private static $_global_model = array();
	
	/**
	 * The constructure initializes the template engine.
	 */
    public function __construct() {
    }

	/**
	 * Sets the name of the view
	 */
	public abstract function setName($viewName);

	/**
	 * Sets the model to be used
	 */
	public abstract function setModel($key, $value);

	/**
	 * Renders
	 */
	public abstract function render();
	
	/**
	 * Factory method to create view objects
	 */
	public static function create($viewName)
	{
		$viewClassName = VIEW_CLASS;
		require_once FRAMEWORK_PATH . $viewClassName . '.php';
		$view = new $viewClassName;
		$view->setName($viewName);
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
