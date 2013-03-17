<?php

require_once CLASSES_PATH . 'utils.php';

class TemplateManager 
{
	const TPL_FILE_SUFIX = '.tpl.php';
	
	public $templates = array();

	public function save($template_details)
	{
	}
	
	public function delete($template_id)
	{
	}
	
	/**
	 * Returns a proejct details
	 */	 
	public function get($template_id)
	{
		$filename = $template_id . self::TPL_FILE_SUFIX;
		return $this->read_file($filename);
	}
	
	/**
	 * Return the list of project details as retrived from XML.
	 */
	public function get_list($pattern = '*')
	{
		$templates = null;
		$template_files = get_files(CODE_TEMPLATE_PATH , self::TPL_FILE_SUFIX, $pattern);
		if (!empty($template_files)) {
			$templates = array();
			foreach($template_files as $template_file) {
				$templates[] = array('name' => $template_file[0]);
			}
		}
		return $templates;
	}
	
	/**
	 * id_exists.
	 * 
	 * @param 
	 * @return
	 */
	public function id_exists($project_id)
	{
	}
	
	/**
	 * Factory function that returns the instance of the Project Manager 
	 */
	public static function instance()
	{
		return new TemplateManager();
	}
	
	public function validate($project_details)
	{
		$invalid_fields = array();
		if (trim($project_details['id']) == false) {
			$invalid_fields['id'] = 'ID is required';
		}
		if (trim($project_details['name']) == false) {
			$invalid_fields['name'] = 'Name is required';
		}
		return $invalid_fields;
	}
	
}
