<?php

require_once LIB_PATH . 'Savant3.php';

/**
 * The Savant3 template abstraction class
 *
 * @author Young Suk Ahn
 */
class SavantView extends View {

  private $resource_name; // the actual recource name derived from the view name
	private $template;
	
	/**
	 * The constructure initializes the template engine.
	 */
    public function __construct() {
		parent::__construct();
        $this->template = new Savant3();
        $this->template->setPath('template', VIEWS_PATH);
    }
	public function add_path($template_path) {
		parent::add_path($template_path);
		$this->template->setPath('template', array($template_path, VIEWS_PATH));
	}
	
	/**
	 * Sets the name of the view
	 */
	public function set_name($view_name)
	{
    $this->view_name = $view_name;
		$this->resource_name = $view_name . '.tpl.php';
	}

	/**
	 * Sets the model to be used
	 */
	public function set_model($key, $value)
	{
		$this->template->$key = $value;
	}
	
	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
    return null;
	}
	public function __set($key, $value) {
		$this->template->$key = $value;
	}

	/**
	 * Renders
	 */
	public function render() {
		$this->template->display($this->resource_name);
	}
	
	public function __toString()
	{
		return $this->template->getOutput($this->resource_name);
	}

}
