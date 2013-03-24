<?php
  
require_once  '../config.php';
require_once  CLASSES_PATH . 'TemplateManager.php';
require_once  CLASSES_PATH . 'Ds_Introspector_Hbm.php';
require_once  CLASSES_PATH . 'CodeGen_PhpTemplate.php';

class CodeGenPhpTest extends PHPUnit_Framework_TestCase
{
	public function testManager() {
		
		$db_intros = new Ds_Introspector_Hbm();
		$db_intros->create_connection('C:/appservers/webapps/codini/repo/projects/data-schemas/', 'test', 'test', 'seedni');
		$schema = $db_intros->get_schema('ATest.hbm.xml');

		$template_details = TemplateManager::instance()->get('db_mysql_ddl');
		
		$codgen = new CodeGen_PhpTemplate(CODE_TEMPLATE_PATH);
		echo $codgen->get_genpath($template_details, $schema, null);
		$this->assertNotNull($template_details);
	}
	
}