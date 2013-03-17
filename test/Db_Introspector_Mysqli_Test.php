<?php

define('APP_ROOT', 'C:/appservers/webapps/codini/');
define('COMPONENTS_ROOT', 'protected/components/');

require_once COMPONENTS_ROOT . 'Db_Introspector.php';
require_once COMPONENTS_ROOT . 'Db_Introspector_MySqli.php';
require_once COMPONENTS_ROOT . 'DataStructure_Serializer.php';
require_once COMPONENTS_ROOT . 'CodeGen_PhpTemplate.php';

class Db_Introspector_MySqli_Test extends PHPUnit_Framework_TestCase
{
	public function test1() {
		
		$db_intros = new Db_Introspector_MsSql();
		
		$db_intros->create_connection('localhost', 'test', 'test', 'test');
		
		$tables = $db_intros->get_table_list();
		
		//$this->assertTrue($tables != null); 
		
		print "TABLES: ";
		//print_r($tables);
		
		$table_meta = $db_intros->get_table_metadata('test_table');
		
		print "DESC test_table: ";
		//print_r($table_meta);
		
		$serialize = new DataStructure_Serializer_Xml();
		$xml = $serialize->serialize($table_meta);
		
		//print_r($xml);
		
		$template_dir = 'C:/appservers/webapps/codini/code_templates/';
		$code_generator = new CodeGen_PhpTemplate($template_dir);
		
		$gen = $code_generator->generate('php_model.tpl.php', $table_meta);
		echo $gen;
	}
}