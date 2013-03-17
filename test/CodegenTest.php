<?php
   
require INCLUDE_PATH . '/includes/classes/db_class.php';
require INCLUDE_PATH . '/includes/utility_functions.php';
require INCLUDE_PATH . '/includes/error_handlers.php';
require INCLUDE_PATH . '/includes/classes/mystats_class.php';
require INCLUDE_PATH . '/includes/classes/cache_class.php';
require INCLUDE_PATH . '/includes/language_functions.php';
require INCLUDE_PATH . '/includes/validation_functions.php';
require INCLUDE_PATH . '/includes/session_functions.php';
require INCLUDE_PATH . '/includes/classes/customer_class.php';
require INCLUDE_PATH . '/includes/autoloader.php';

define('APP_ROOT', 'C:/appservers/webapps/codini/');
define('COMPONENTS_ROOT', '');

require_once COMPONENTS_ROOT . 'Db_Introspector.php';
require_once COMPONENTS_ROOT . 'Db_Introspector_MySqliWf.php';
require_once COMPONENTS_ROOT . 'DataStructure_Serializer.php';
require_once COMPONENTS_ROOT . 'CodeGen_PhpTemplate.php';

class CodegenTest extends PHPUnit_Framework_TestCase
{
	public function test1() {
		
		$db_intros = new Db_Introspector_MySqliWf();
		
		$db_intros->create_connection('localhost', 'test', 'test', 'OT');
		
		$tables = $db_intros->get_table_list('tblQuote%');
		
		//$this->assertTrue($tables != null); 
		
		//print "TABLES: ";
		//print_r($tables);
		
		$table_meta = $db_intros->get_table_metadata('tblQuote');
		
		//print "DESC : ";
		//print_r($table_meta);
		
		$serialize = new DataStructure_Serializer_Xml();
		$xml = $serialize->serialize($table_meta);
		
		//print_r($xml);
		
		$template_dir = './';
		$code_generator = new CodeGen_PhpTemplate($template_dir);
		
		$gen = $code_generator->generate('php_dao_wf.tpl.php', $table_meta);
		echo $gen;
	}
}