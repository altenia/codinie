<?php

define('APP_PATH', 'C:/appservers/webapps/codini/application/');
define('CLASSES_PATH', APP_PATH . 'classes/');
define('CODE_TEMPLATE_PATH', APP_PATH.'code-templates/');

require_once CLASSES_PATH . 'Db_Introspector.php';
require_once CLASSES_PATH . 'Db_Introspector_MySqli.php';
require_once CLASSES_PATH . 'DataStructure_Serializer.php';
require_once CLASSES_PATH . 'CodeGen_PhpTemplate.php';

class DsIntrospectorMySqli_Test extends PHPUnit_Framework_TestCase
{
	public function test1() {
		
		$db_intros = new Db_Introspector_MySqli();
		
		$db_intros->create_connection('localhost', 'test', 'test', 'test');
		
		$tables = $db_intros->get_table_list();
		
		//$this->assertTrue($tables != null); 
		
		print "TABLES: ";
		//print_r($tables);
		
		$table_meta = $db_intros->get_table_metadata('test_table');
		
		print "DESC test_table: ";
		print_r($table_meta);
		
		$serialize = new DataStructure_Serializer_Xml();
		$xml = $serialize->serialize($table_meta);
		
		//print_r($xml);
		
		$code_generator = new CodeGen_PhpTemplate(CODE_TEMPLATE_PATH);
		
		$gen = $code_generator->generate('php_model.tpl.php', $table_meta);
		echo $gen;
	}
}