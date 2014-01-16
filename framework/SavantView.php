<?php
/**
 * This file contains the implementation of the concrete view class that wraps the Savant template.
 *
 * PHP version 5
 * 
 * @author    Young Suk Ahn <ysahn@altenia.com>
 */

Loader::load('savant/Savant3b.php', Loader::LOC_LIB);

/**
 * The Savant3 template abstraction class
 *
 * This class is a concrete view class that wraps Savant3 template library.
 *
 */
class SavantView extends View
{
  public $resource_name; // the actual resource name derived from the view name
  private $_template;

  /**
   * The constructor. Instantiates the template engine.
   */
  public function __construct() {
    parent::__construct();
    $this->_template = new Savant3();
    $this->_template->setPath('template', SHARED_VIEWS_PATH);
  }

  /**
   * Adds a path where templates are searched for.
   *
   * @param string $template_path The path of where the templates are located.
   *
   * @return none
   */
  public function add_path($template_path) {
    parent::add_path($template_path);
    $this->_template->setPath('template', array($template_path, SHARED_VIEWS_PATH));
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
    $this->resource_name = $view_name . '.tpl.php';
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
    $this->_template->$property_name = $value;
  }
  
  /**
   * Renders this view. 
   * Echo the applied template.
   * 
   * @return none
   */
  public function render() {
    $this->_template->display($this->resource_name);
  }
  
  /**
   * Returns the output of the applied view.
   * 
   * @return string The result of the applied template
   */
  public function __toString() {
    return $this->_template->getOutput($this->resource_name);
  }

}
