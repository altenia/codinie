<?php
  
require_once  '../config.php';
require_once  CLASSES_PATH . 'ProjectManager.php';

class ProjectManagerTest extends PHPUnit_Framework_TestCase
{
	public function test1() {
		
		$prjMgr = ProjectManager::instance();
		$prjs = $prjMgr->get_list();
		
		$prj = $prjMgr->get('project1');
		$this->assertNotNull($prj);
		
		$prj_xml = $prjMgr->to_xml($prj);
		$this->assertNotNull($prj);
		
		$id_exists = $prjMgr->id_exists('project');
		if ($id_exists)
			print ("file exists");
		else 
			print ("file NOT exists");
		
		print_r($prj_xml);
		
		$trim_res = trim('');
		if ($trim_res == true) {
			print_r( '$trim_res is true ');
		}
		if ($trim_res == false) {
			print_r( '$trim_res is false ');
		}
	}
	
}