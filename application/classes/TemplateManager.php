<?php

require_once CLASSES_PATH . 'utils.php';

class TemplateManager 
{
	const TPL_FILE_SUFIX = '.tpl.php';
	const INFO_FILE_SUFIX = '.tpl.info';
	
	public $template_details = array();

	/**
	 *
	 * @param array $template_details associative array  with keys:
	 * 				'id'      => the id of the template
	 *				'content' => 
	 */
	public function save($template_details)
	{
		$file_path = CODE_TEMPLATE_PATH . $template_details['id'] . self::TPL_FILE_SUFIX;
		
		save_to_file($file_path, $template_details['content']);
	}
	
	public function delete($template_id)
	{
	}
	
	/**
	 * Returns a template details
	 */	 
	public function get($template_id)
	{
		$filepath_base = CODE_TEMPLATE_PATH . $template_id;
		if (!file_exists($filepath_base . self::TPL_FILE_SUFIX)) {
			throw new Exception('Template [' . $template_id . '] not found');
		}
		
		$template_details = array();

		$info_filepath = CODE_TEMPLATE_PATH . $template_id . self::INFO_FILE_SUFIX;
		if (file_exists($info_filepath)) {
			$template_details = parse_ini_file($info_filepath);
		}
		
		$template_details['id'] = $template_id;	
		$template_details['content'] = file_get_contents($filepath_base . self::TPL_FILE_SUFIX);
		
		return $template_details;
	}
	
	/**
	 * Return the list of templates details (without the actual template).
	 */
	public function get_list($pattern = '*')
	{
		$template_details = null;
		$template_files = get_files(CODE_TEMPLATE_PATH , self::TPL_FILE_SUFIX, $pattern);
		if (!empty($template_files)) {
			$template_details = array();
			foreach($template_files as $template_id) {
				// read the info file
				$info_filepath = CODE_TEMPLATE_PATH . $template_id[0] . self::INFO_FILE_SUFIX;
				if (file_exists($info_filepath)) {
					$info_array = parse_ini_file($info_filepath);
					$template_details[] = array_merge($info_array, array('id' => $template_id[0]));
					
				} else {
					$template_details[] = array('id' => $template_id[0], 'name' => $template_id[0],  'description' => '', 'unit' => 'undefined');
				}
			}
		}
		return $template_details;
	}
	
	/**
	 * id_exists.
	 * 
	 * @param 
	 * @return
	 */
	public function id_exists($template_id)
	{
		return file_exists(CODE_TEMPLATE_PATH . $template_id . self::TPL_FILE_SUFIX);
	}
	
	/**
	 * Factory function that returns the instance of the Project Manager 
	 */
	public static function instance()
	{
		return new TemplateManager();
	}
	
	/**
	 * Validates that a template's details contains minimul required fields
	 */
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
	
}
