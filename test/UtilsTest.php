<?php
  
require_once  '../config.php';
require_once  CLASSES_PATH . 'utils.php';

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
	
}