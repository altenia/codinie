<?php
/**
 * This file contains the implementation of a PhpView
 *
 * @author Young Suk Ahn <ysahn@altenia.com>
 */
 
 
/**
 * This class is a implementation of a pure PHP template.
 * It uses ob_start() and ob_get_contents() to apply template.
 *
 */
class PhpView extends View
{
  private $_do_compress_ob = false;
  private $_do_extract_vars = false;
  public $resource_name; // the actual recource name derived from the view name
  
  /**
   * The constructor. 
   */
  public function __construct() {
    parent::__construct();
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
   
    if (!empty($init_params) && array_key_exists('compress', $init_params) ) {
      $this->_do_compress_ob = $init_params['compress'];
    }
    if (!empty($init_params) && array_key_exists('extract-vars', $init_params) ) {
      $this->_do_extract_vars = $init_params['extract-vars'];
    }
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
   * Returns the string of the applied template
   * 
   * @return string The result of the applied template
   */
  public function __toString() {
    return $this->get_output($this->resource_paths[0] . $this->resource_name);
  }

  /**
   * Returns the output of applying template provided
   *
   * @param string $template_url The full path of the template
   * 
   * @return string The result of the applied template
   */
  private function get_output($template_url) {
    // Extract variables and make it available in the template
    foreach (View::get_shared_data() as $key => $val) {
      if (!isset($this->$key)) {
        $this->$key = $val;
      }
    }
    // Import view's variables into the current (root) symbol table
    if ($this->_do_extract_vars) {
      extract(get_object_vars($this), EXTR_REFS);
    }

    // Do ob_start with or without compression
    if ($this->_do_compress_ob) {
      ob_start('ob_gzhandler');
    } else {
      ob_start();
    }
    
    include $template_url;

    $result = ob_get_contents();
    ob_end_clean();

    return $result;
  }
}
