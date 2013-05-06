<?php
/**
 * This file contains the implementation of the abstract view class which serves as the base class of all view classes.
 *
 * @author Young Suk Ahn <ysahn@altenia.com>
 */

/**
 * The base class of all View classes.
 * A view object is responsible of rendering the presentation (UI) to the client (e.g. browser).  
 */
abstract class View
{
  protected $view_name;
  protected $resource_paths; /* The path of the actual physical resource (i.e. template) */

  private static $_shared_data = array();

  /**
   * The constructor. 
   */
  public function __construct() {
    $this->resource_paths = array(SHARED_VIEWS_PATH);
  }
  
  /**
   * Adds a path where templates are searched for.
   *
   * @param string $template_path The path of where the templates are located.
   *
   * @return none
   */
  public function add_path($template_path) {
    array_unshift($this->resource_paths, $template_path);
  }

  /**
   * initializes the view.
   * Concrete classes must implement this method accordingly.
   * 
   * @param string $view_name   The name of this view
   * @param string $init_params The optional parameters to initialize the view.
   * 
   * @return none
   */
  public abstract function init($view_name, $init_params);
  
  /**
   * Sets the name of the view
   * Concrete classes must implement this method accordingly.
   *
   * @param string $view_name The name of this view
   *
   * @return none
   */
  public function set_name($view_name) {
    $this->name  = $view_name;
  }

  /**
   * Renders this view.
   * Concrete classes must implement this method accordingly.
   * 
   * @return none
   */
  public abstract function render();


  /**
   * Factory method to create view objects
   *
   * @param string $view_name       The name of the view (notice, view name is usually template name w/o the extension)
   * @param string $init_params     The optional initial configuration parameters.
   * @param string $template_path   The path where the templates are. If non  is provided, searches in the common view path.
   * @param string $view_class_name The name of the view class. If not specified, default is the 
   *
   * @return View The newly created view object.
   */
  public static function create($view_name, $init_params = null, $template_path = null, $view_class_name = VIEW_CLASS) {
    include_once FRAMEWORK_PATH . $view_class_name . '.php';
    $view = new $view_class_name;

    $view->init($view_name, $init_params);
    if (!empty($template_path)) {
      $view->add_path($template_path);
    }
    return $view;
  }

  /**
   * Sets a variable which is used to populate the view.
   *
   * @param string $key   The key of the data
   * @param mixed  $value The value of the data
   *
   * @return View The newly created view object.
   */
  public static function set_shared_data($key, $value) {
    View::$_shared_data[$key] = $value;
  }

  /**
   * Returns the associative array of view data
   *
   * @return array The associative array that contains all the data for the view.
   */
  public static function get_shared_data() {
    return View::$_shared_data;
  }
  
}
