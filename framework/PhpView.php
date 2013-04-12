<?php
/**
 * This file contains the implementation of a PhpView
 *
 * @author Young Suk Ahn
 */
 
 
/**
 * This class is a bare implementation of a PHP template.
 * It uses ob_start() and ob_get_contents() to apply template.
 *
 */
class PhpView extends View {
	
  private $resource_name; // the actual recource name derived from the view name
  
	/**
	 * The constructure initializes the template engine.
	 */
  public function __construct() {
    parent::__construct();
  }

	
	/**
	 * Sets the name of the view
	 */
	public function set_name($view_name)
	{
		$this->resource_name = $view_name . '.tpl.php';
	}

	/**
	 * Sets the model to be used
	 */
	public function set_model($key, $value)
	{
		$this->$key = $value;
	}
	
	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
    return null;
	}
	public function __set($key, $value) {
		$this->$key = $value;
	}

	/**
	 * Renders
	 */
	public function render() {
    echo $this->__toString();
	}
	
  /**
   * Returns the string of the applied template
   */
	public function __toString()
	{
		return $this->get_output($this->resource_paths[0] . $this->resource_name);
	}

  /**
   * Returns the output of applying template provided
   *
   * @param $template_url The full path of the template
   */
  private function get_output($template_url)
  {
    // Extract variables and make it available in the template
    foreach (View::get_globals() as $key => $val) {
      if (!isset($this->$key)) {
        $this->$key = $val;
      }
    }
		extract(get_object_vars($this), EXTR_REFS);
    
    ob_start();
    
		require($template_url);
		
		$result = ob_get_contents();
		ob_end_clean();
		
		return $result;
  }
}
