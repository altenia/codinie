<?php

require_once CLASSES_PATH . 'utils.php';

class TemplateManager 
{
	const TPL_FILE_SUFIX = '.tpl.php';
	
	public $templates = array();

	/**
	 *
	 * @param array $template_details associative array  with keys:
	 * 				'id'      => the id of the template
	 *				'content' => 
	 */
	public function save($template_details)
	{
		$file_path = CODE_TEMPLATE_PATH . $template_details['id'] . self::TPL_FILE_SUFIX;
		
		//print_r($file_path); die();
		save_to_file($file_path, $template_details['content']);
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
				$templates[] = array('id' => $template_file[0]);
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
	
	public function validate($template_details)
	{
		$invalid_fields = array();
		// Trim is empty
		if (trim($template_details['id']) == false) {
			$invalid_fields['id'] = 'ID is required';
		}
		if (trim($template_details['content']) == false) {
			$invalid_fields['content'] = 'Name is required';
		}
		return $invalid_fields;
	}
	
	public function read_file($filename)
    {
		$file_path = CODE_TEMPLATE_PATH . $filename;
		if (!file_exists($file_path)) {
			return null; 
		}
		$content = read_from_file($file_path);

		return $content;
	}
}
