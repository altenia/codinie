<?php
  
require_once  '../config.php';
require_once  CLASSES_PATH . 'utils.php';
require_once  CLASSES_PATH . 'DataStructure.php';

class UtilsTest extends PHPUnit_Framework_TestCase
{
	public function testArrayKeyExistsMd() {
		
		$keys = array('a', 'b');
		$multidim = array('a' => array('b' => array('c' => 'hello') ) );
 
		$result = array_key_exists_md($keys, $multidim); 
		$this->assertTrue($result);
		
		$keys = array('a', 'b', 'd');
		$result = array_key_exists_md($keys, $multidim); 
		$this->assertFalse($result);
	}
	
	public function testFile() {
		$temp_file = "./test.file";
		save_to_file($temp_file, "Hello World");
		
		$data = read_from_file($temp_file);
		$this->assertEquals("Hello World", $data);
		unlink($temp_file);
	}
	
	public function testDataStructure() {
		$entity = new DataStructure(null, "a.b.c.PackagedClassName");
		
		//echo '[' . $entity->namespace . "]\n";
		//echo '[' . $entity->get_fqns(true). "]\n";
		$this->assertEquals('PackagedClassName', $entity->name);
		$this->assertEquals('a.b.c', $entity->namespace);
		$this->assertEquals('a.b.c.PackagedClassName', $entity->get_fqn());
		$this->assertEquals('a.b.c.', $entity->get_fqns(true));
		$this->assertEquals('a.b.c', $entity->get_fqns(false));
		
		$entity = new DataStructure(null, "ClassName");
		$this->assertEquals('ClassName', $entity->name);
		$this->assertEquals('', $entity->namespace);
		$this->assertEquals('ClassName', $entity->get_fqn());
		$this->assertEquals('', $entity->get_fqns(true));
		$this->assertEquals('', $entity->get_fqns(false));
	}
}