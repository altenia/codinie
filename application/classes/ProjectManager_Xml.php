<?php

require_once CLASSES_PATH . 'ProjectManager.php';
require_once CLASSES_PATH . 'utils.php';

/**
 * Project information stored in filesystem in XML format.
 * Each XML file (<projectid>.prj.xml is a project
<project>
	<name></name>
	<owner></owner>
	<created></created>
	<last-modif></last-modif>
	<data-source>
		<type></type>
		<url></url>
		<username></username>
	</data-source>
	
	<active-templates>
		<template name="" />
		<template name="" />
		<template name="" />
	</active-templates>
	
	<params>
		<param name="" value="" />
	</params>
</project>
 */
class ProjectManager_Xml extends ProjectManager
{
	const FILE_SUFFIX = '.prj.xml';
	
	public function save($prj_details) 
	{
		if (empty($prj_details)) {
			return false;
		}
		$error_fields = $this->validate($prj_details);
		
		if (empty($error_fields)) {
			$project_id = $prj_details['id'];
			$this->projects[$prj_details['id']] = $prj_details;
			$xml = $this->to_xml($prj_details);
			// save xml
			$file_path = PROJECTS_PATH . $project_id . self::FILE_SUFFIX;
			$fh = fopen($file_path, 'w');
			fwrite($fh, $xml);
			fclose($fh);
		}
		
		return true;
	}
	
	public function delete($prj_id) 
	{
	}
	
	public function get($prj_id) 
	{
		if ( array_key_exists($prj_id, $this->projects) ) {
			return $this->projects[$prj_id];
		}
		$project = new SimpleXMLElement(PROJECTS_PATH . $prj_id . self::FILE_SUFFIX, NULL, TRUE);
		if (!empty($project) ) {
			$project = $this->to_assoc_array($project);
			$project['id'] = $prj_id;
			$this->projects[$prj_id] = $project;
		}
		return $project;
	}
	
	/**
	 * Return the list of project details as retrived from XML.
	 */
	public function get_list()
	{
	//print_r(self::FILE_SUFFIX); die();
		$files = get_files(PROJECTS_PATH, self::FILE_SUFFIX, '*');
		
		$projects = array();
		foreach ($files as $file) {
			$project = new SimpleXMLElement(PROJECTS_PATH . $file[0] . self::FILE_SUFFIX, NULL, TRUE);
			$project->id = $file[0];
			$projects[] = $project;
		}
		return $projects;
	}
	
	public function id_exists($project_id)
	{
		$file = get_files(PROJECTS_PATH, self::FILE_SUFFIX, $project_id);
		
		return !empty($file);
	}

	
	/**
	 * Generates associative array from XML that represents a project
	 */
	private function to_assoc_array($project_xml) 
	{
		$project_details = array();
		$project_details['id'] = (string)$project_xml->id;
		$project_details['name'] = (string)$project_xml->name;
		$project_details['owner'] = (string)$project_xml->owner;
		$project_details['language'] = (string)$project_xml->language;
		$project_details['description'] = (string)$project_xml->description;
		$project_details['created'] = (string)$project_xml->created;
		$project_details['last-modif'] = (string)$project_xml->{'last-modif'};
		$project_details['data-source'] = array();
		$project_details['data-source']['type'] = (string)$project_xml->{'data-source'}->type;
		$project_details['data-source']['url'] = (string)$project_xml->{'data-source'}->url;
		$project_details['data-source']['username'] = (string)$project_xml->{'data-source'}->username;
		
		//print_r($project_xml->{'active-templates'}); die();
		if (isset($project_xml->{'active-templates'})) {
			$project_details['active-templates'] = array();
			foreach($project_xml->{'active-templates'}->template as $active_template) {
				$project_details['active-templates'][(string)$active_template->attributes()->name] = true;
			}
		}
		if (isset($project_xml->{'params'})) {
			$project_details['params'] = array();
			foreach($project_xml->{'params'}->param as $param) {
				$project_details['params'][(string)$param->attributes()->name] = (string)$param->attributes()->value;
			}
		}
		
		//print_r($project_details); die();
		return $project_details;
	}
	
	/**
	 * Generates XML from the associative array that represents a project
	 */
	public function to_xml($project_details)
	{
		//print_r($project_details);
		$out_xml = "<project>\n";
		$out_xml .= "\t<name>".$project_details['name'] . "</name>\n";
		if (array_key_exists('owner', $project_details)) 
			$out_xml .= "\t<owner>" . $project_details['owner'] . "</owner>\n";
		if (array_key_exists('language', $project_details)) 
			$out_xml .= "\t<language>" . $project_details['language'] . "</language>\n";
		if (array_key_exists('description', $project_details)) 
			$out_xml .= "\t<description>" . $project_details['description'] . "</description>\n";
		if (array_key_exists('created', $project_details)) 
			$out_xml .= "\t<created>" . $project_details['created'] . "</created>\n";
			
		$project_details['last-modif'] = date(DATE_TIME_FORMAT);
		$out_xml .= "\t<last-modif>" . $project_details['last-modif'] . "</last-modif>\n";
		$out_xml .= "\t<data-source>\n";
		$out_xml .= "\t\t<type>" . $project_details['data-source']['type'] . "</type>\n";
		$out_xml .= "\t\t<url>" . $project_details['data-source']['url'] . "</url>\n";
		$out_xml .= "\t\t<username>" . $project_details['data-source']['username'] . "</username>\n";
		$out_xml .= "\t</data-source>\n";
	
		//print_r($project_details); die();
		if (array_key_exists('active-templates', $project_details)) {
			$out_xml .= "\t<active-templates>\n";
			foreach($project_details['active-templates'] as $active_template => $active) {
				$out_xml .= "\t\t<template name=\"" . $active_template . "\" />\n";
			}
			$out_xml .= "\t</active-templates>\n";
		}
	
		//print_r($project_details); die();
		if (array_key_exists('params', $project_details)) {
			$out_xml .= "\t<params>\n";
			foreach($project_details['params'] as $pname => $pval) {
				$out_xml .= "\t\t<param name=\"" . $pname . "\" value=\"" . $pval . "\" />\n";
			}
			$out_xml .= "\t</params>\n";
		}
		$out_xml .= "</project>\n";
		return $out_xml;
	}
}