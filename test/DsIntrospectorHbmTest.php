<?php

define('APP_PATH', 'C:/appservers/webapps/codini/application/');
define('CLASSES_PATH', APP_PATH.'classes/');
define('CODE_TEMPLATE_PATH', APP_PATH.'code-templates/');

require_once CLASSES_PATH . 'Ds_Introspector.php';
require_once CLASSES_PATH . 'Ds_Introspector_Hbm.php';
require_once CLASSES_PATH . 'DataStructure_Serializer.php';
require_once CLASSES_PATH . 'CodeGen_PhpTemplate.php';

class DsIntrospectorHbmTest extends PHPUnit_Framework_TestCase
{
	public function test1() {
		
		$db_intros = new Ds_Introspector_Hbm();
		$db_intros->create_connection('C:/appservers/webapps/codini/repo/projects/data-schemas/', 'test', 'test', '');
		
		$tables = $db_intros->get_table_list('*');
		
		//$this->assertTrue($tables != null); 
		//print "TABLES: ";
		//print_r($tables);
		
		$table_meta = $db_intros->get_schema($tables[0]['table_name']);
		print "DESC : ";
		print_r($table_meta);
		
		/*
		$serialize = new DataStructure_Serializer_Xml();
		$xml = $serialize->serialize($table_meta);
		
		//print_r($xml);
		
		$template_dir = './';
		$code_generator = new CodeGen_PhpTemplate($template_dir);
		
		$gen = $code_generator->generate('php_dao_wf.tpl.php', $table_meta);
		echo $gen;
		*/
	}
}