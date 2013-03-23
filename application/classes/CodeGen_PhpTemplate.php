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

	function generate($template_details, $schema, $params = null)
	{
		$unit = ifndef('unit', $template_details, 'schema');
		if ($unit == 'schema') {
			$template_input = array(
					'schema' => $schema,
					'params' => $params
					);
			extract($template_input);

			ob_start();
			require_once($this->template_root_dir . 'phptemplate_helper.php');
			//require($this->template_root_dir . $template_name);
			eval('?>' . $template_details['content']);

			$applied_template = ob_get_contents();
			ob_end_clean();
			return $applied_template;
		} else {
			$applied_template = array();
			foreach($schema->entities as $entity) {
				$template_input = array(
						'schema' => $schema,
						'entity' => $entity,
						'params' => $params
						);
				extract($template_input);

				$schema_name = $schema->name;
				$entity_name = $entity->name;
				$filename = $entity_name;
				//$filename_expr = "\$filename = " . ifndef('file_name', $template_details, $entity->name) . ";";
				//eval("\$filename = 'entity_name' . '.php';");

				ob_start();
				
				require_once($this->template_root_dir . 'phptemplate_helper.php');
				//require($this->template_root_dir . $template_name);
				eval('?>' . $template_details['content']);

				
				$applied_template[$filename . ifndef('file_suffix', $template_details, '')] = ob_get_contents();
				ob_end_clean();
			}
			return $applied_template;
		}
	}
	
}