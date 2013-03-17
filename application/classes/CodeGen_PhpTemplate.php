<?php

class CodeGen_PhpTemplate {

	/** the root directory where the template resides **/
	private $template_root_dir;
	
	function __construct($template_root_dir){
		$this->template_root_dir = $template_root_dir;
		if (!is_dir($this->template_root_dir))
			throw new Exception ('Not a valid directory');
		// $this->template_root_dir add / as needed
	}

	function generate($template_name, $schema, $params = null)
	{
		$template_input = array(
				'params' => $params, 
				'schema' => $schema);
		extract($template_input);

		ob_start();
		require_once($this->template_root_dir . 'phptemplate_helper.php');
		require($this->template_root_dir . $template_name);

		$applied_template = ob_get_contents();
		ob_end_clean();

		return $applied_template;
	}
	
}