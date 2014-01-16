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

	/**
	 * Applies template and returns an associative array where the key is the filename
	 */
	function generate($template_details, $schema, $params = null)
	{
		$unit = ifndef('unit', $template_details, 'schema');
		$applied_template = array();
		if ($unit == 'schema') {
			$template_input = array(
						'schema' => $schema,
						'params' => $params
						);
			
			$path_id = $this->get_genpath($template_details, $schema, null);

			$applied_template[$path_id] = $this->apply_template($template_input, $template_details['content']);
		} else {
			foreach($schema->entities as $entity) {
				$template_input = array(
						'schema' => $schema,
						'entity' => $entity,
						'params' => $params
						);
				$path_id = $this->get_genpath($template_details, $schema, $entity);
				$applied_template[$path_id] = $this->apply_template($template_input, $template_details['content']);
			}
		}
		return $applied_template;
	}
	
	function apply_template($template_input, $template_content)
	{
		extract($template_input);

		ob_start();
		
		require_once($this->template_root_dir . 'phptemplate_helper.php');
		//require($this->template_root_dir . $template_name);
		eval('?>' . $template_content);
		
		$result = ob_get_contents();
		ob_end_clean();
		
		return $result;
	}
	
	/**
	 * Returns the path of the generated cod in the format
	 * [<path>/]filename
	 * where path is namesapace with dots replaced by the character '/'
	 */
	function get_genpath($template_details, $schema, $entity = null) 
	{
		$schema_name = $schema->name;
		$entity_name ='';
		$filename_default = null;
		$path = !empty($schema_name) ? $schema_name . '.' : '';
		if ($entity != null) {
			$entity_name =  $entity->name;
			$filename_default = $entity_name;
			$path = $entity->get_fqns(true);
		} else {
			$filename_default = $schema_name;
		}

		$path = str_replace('.' , '/', $path);
		$filename_expr = "\$filename = " . ifndef('file_name', $template_details, $filename_default) . ";";
//		print ($template_details['id'] . ":" . $schema_name. ":" .$filename_expr . "<br>");
		//eval($filename_expr);
		$filename = $filename_default . ifndef('file_suffix', $template_details, '');

		// If filename too short (or empty), just use the template id
		if (strlen($filename) < 2) {
			$filename = '\'' . $template_details['id'] . '\'';
		}
		
		return $path . $filename;
	}
}