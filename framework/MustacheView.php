<?php
/**
 * This file contains the implementation of the concrete view class that wraps the Moustache template.
 *
 * PHP version 5
 * 
 * @author    Young Suk Ahn <yahnpark@wayfair.com>
 * @copyright 2011 Wayfair, LLC - All rights reserved
 * @version   SVN: $Id
 */

Loader::load('Mustache/Autoloader.php', Loader::LOC_LIB);
Mustache_Autoloader::register();

/**
 * This class is a concrete view class that wraps Mustache template engine.
 *
 */
class MustacheView extends View
{
  public $resource_name; // the actual resource name derived from the view name
  private $_template;

  /**
	 * The constructor. Instantiates the template engine.
  */
  public function __construct() {
    parent::__construct();
    $this->_template = new Mustache_Engine();
  }

  /**
   * Initializes the view
   *
   * @param string $view_name   The name of this view
   * @param string $init_params The optional parameters to initialize the view.
   * 
   * @return none
   */
  public function init($view_name, $init_params) {
     $this->set_name($view_name);
  }

  /**
   * Sets the name of the view
   * 
   * @param string $view_name The name of this view
   * 
   * @return none
   */
  public function set_name($view_name) {
    parent::set_name($view_name);
    $this->resource_name = $view_name . '.moustache';
  }

  /**
   * Gets a local view data
   *
   * @param string $property_name The key of the data to retrieve
   * 
   * @return The value associated to the property name 
   */
  public function __get($property_name) {
    if (property_exists($this, $property_name)) {
      return $this->$property_name;
    }
    return null;
  }

  /**
   * Sets a local view data
   * 
   * @param string $property_name The key of the data
   * @param mixed  $value         The value of the data
   * 
   * @return none
   */
  public function __set($property_name, $value) {
    $this->$property_name = $value;
  }

  /**
   * Renders this view. 
   * Echo the applied template.
   * 
   * @return none
   */
  public function render() {
    echo $this->__toString();
  }

  /**
   * Returns the output of the applied view.
   * 
   * @return string The result of the applied template
   */
  public function __toString() {
    $template_content = file_get_contents($this->resource_paths[0] . $this->resource_name);
    $obj_vars = get_object_vars($this);

    $model = array_merge(View::get_shared_data(), $obj_vars);
    return  $this->_template->render($template_content, $model);
  }

}
