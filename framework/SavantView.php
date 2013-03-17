<?php

require_once 'Savant3.php';

/**
 * The base class of all Controllers
 *
 * @author Young Suk Ahn
 */
class SavantView extends View {

	private $templateName;
	private $template;
	
	/**
	 * The constructure initializes the template engine.
	 */
    public function __construct() {
		parent::__construct();
        $this->template = new Savant3();
        $this->template->setPath('template', VIEWS_PATH);
    }
	
	/**
	 * Sets the name of the view
	 */
	public function setName($viewName)
	{
		$this->templateName = $viewName . '.tpl.php';
	}

	/**
	 * Sets the model to be used
	 */
	public function setModel($key, $value)
	{
		$this->template->$key = $value;
	}
	
	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}
	public function __set($key, $value) {
		$this->template->$key = $value;
	}

	/**
	 * Renders
	 */
	public function render() {
		$this->template->display($this->templateName);
	}
	
	public function __toString()
	{
		return $this->template->getOutput($this->templateName);
	}

}
