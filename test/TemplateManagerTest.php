<?php
  
require_once  '../config.php';
require_once  CLASSES_PATH . 'TemplateManager.php';

class TemplateManagerTest extends PHPUnit_Framework_TestCase
{
	public function testManager() {
		
		$template_details = TemplateManager::instance()->get('db_mysql.ddl');
		$this->assertNotNull($template_details);
	}
	
}