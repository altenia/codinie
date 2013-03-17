<?php

abstract class ProjectManager 
{		
	public $projects = array();

	abstract public function save($prj_details);
	
	abstract public function delete($prj_id);
	
	/**
	 * Returns a proejct details
	 */	 
	abstract public function get($prj_id);
	
	/**
	 * Return the list of project details as retrived from XML.
	 */
	abstract public function get_list();
	
	/**
	 * id_exists.
	 * 
	 * @param 
	 * @return
	 */
	abstract public function id_exists($project_id);
	
	/**
	 * Factory function that returns the instance of the Project Manager 
	 */
	public static function instance()
	{
		require_once 'ProjectManager_Xml.php';
		return new ProjectManager_Xml();
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
	
	public function empty_project_details()
	{
		$project_details = array();
		$project_details['id'] = '';
		$project_details['name'] = '';
		$project_details['language'] = '';
		$project_details['description'] = '';
		$project_details['created'] = '';
		$project_details['last-modif'] = '';
		$project_details['data-source'] = array();
		$project_details['data-source']['type'] = '';
		$project_details['data-source']['url'] = '';
		$project_details['data-source']['username'] = '';
		$project_details['data-source']['db_name'] = '';
		$project_details['active-templates'] = array();
		return $project_details;
	}
}
