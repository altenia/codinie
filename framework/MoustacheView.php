<?php

Loader::load('Mustache/Autoloader.php', Loader::LOC_LIB);
Mustache_Autoloader::register();

/**
 * The Moustache template abstraction class
 *
 * @author Young Suk Ahn
 */
class MoustacheView extends View {

  private $resource_name; // the actual recource name derived from the view name
	private $template;
	
	/**
	 * The constructure initializes the template engine.
	 */
    public function __construct() {
		parent::__construct();
        $this->template = new Mustache_Engine();
    }
	
	/**
	 * Sets the name of the view
	 */
	public function set_name($view_name)
	{
    $this->view_name = $view_name;
		$this->resource_name = $view_name . '.moustache';
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
		$this->$key = $value;
	}

	/**
	 * Renders
	 */
	public function render() {
		echo $this->__toString();
	}
	
	public function __toString()
	{
		$template_content = file_get_contents($this->resource_paths[0] . $this->resource_name);
		$obj_vars = get_object_vars($this);
		
		$model = array_merge(View::get_globals(), $obj_vars);
		return  $this->template->render($template_content, $model);
	}

}
