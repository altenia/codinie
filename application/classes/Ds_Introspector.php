<?php

require_once 'DataStructure.php';

abstract class Ds_Introspector
{
	private $type_mapping = array(
		'bigint' => 'bigint', 
		'binay' => 'binary', 
		'bit' => 'boolean', 
		'blob' => 'blob', 
		'bool' => 'boolean', 
		'boolean' => 'boolean', 
		'clob' => 'text', 
		'char' => 'char', 
		'date' => 'date', 
		'datetime' => 'datetime', 
		'decimal' => 'decimal', 
		'double' => 'double', 
		'enum' => 'enum', 
		'float' => 'float', 
		'int' => 'int', 
		'long' => 'long', 
		'longblob' => 'text', 
		'longtext' => 'text', 
		'mediumblob' => 'blob', 
		'mediumint' => 'int', 
		'mediumtext' => 'text', 
		'numeric' => 'double', 
		'real' => 'double', 
		'smallint' => 'short', 
		'text' => 'text', 
		'time' => 'time', 
		'timestamp' => 'timestamp', 
		'tinyblob' => 'blob', 
		'tinyint' => 'short', 
		'tinytext' => 'text', 
		'varchar' => 'string'
		);

	protected $connection;
	
	/**
	 * Sets (overrides)
	 * type mapping
	 */
	protected function set_type_mapping($type_name, $canonical_type)
	{
		$this->type_mapping[$type_name] = $canonical_type;
	}
	
	/**
	 * @param $type_mappings associative array of mappings
	 */
	protected function set_type_mappings($type_mappings)
	{
		$this->type_mapping = array_merge($this->type_mapping, $type_mappings);
	}
	
	/**
	 * Returns the canonical type
	 */
	protected function get_type_mapping($type_name, $preserve_case = false)
	{
		$openParenPos = strpos($type_name, '(');
		$type_name = (string)$type_name;
		if ($openParenPos > 0) {
			$type_name = substr($type_name, 0, $openParenPos);
		}
		if (!$preserve_case) {
			$type_name = strtolower($type_name);
		}

		if (array_key_exists( $type_name, $this->type_mapping)) {
			return $this->type_mapping[$type_name];
		}
		return $type_name;
	}
	
	/**
	 *
	 */
	public function set_connection($connection)
	{
		$this->connection = $connection;
	}
	
	/**
	 *
	 */
	abstract public function get_table_list($name_pattern);
	
	/**
	 * Returns the schema 
	 * a schema is a list of DataStructures
	 * 
	 */
	abstract public function get_schema($table_name);
	
	///////// Field attributes //////////
	
	/**
	 * Returns true if is nullable - opposite of (not-null)
	 */
	protected abstract function is_nullable($param);
	
	/**
	 * Returns true if is the field is a key
	 */
	protected abstract function is_key($param);
	
	/**
	 * Returns the default value if defined, null otherwise
	 */
	private function default_val($param)
	{
		return $val;
	}
	
	/**
	 * Returns true if is an identity (e.g. auto_increment)
	 */
	private function is_identity($param)
	{
		return false;
	}
	
	/**
	 * Returns true if the column has unique constraint
	 */
	protected abstract function is_unique($param);
	
	
	/**
	 * Returns the minimum length (by default is null) 
	 */
	protected function min_length($param)
	{
		return null;
	}
	
	/**
	 * Returns the max length (by default is null) 
	 */
	protected function max_length($param)
	{
		return null;
	}
	
	/**
	 * Returns true if the field is updatable (i.e. non read-only) 
	 */
	protected function is_updatable($param)
	{
		return true;
	}
	
	/**
	 * Returns the if the field is insertable (i.e. non system set)  
	 */
	protected function is_insertable($param)
	{
		return true;
	}
	
	/**
	 * Returns true if the field is index in search engine
	 */
	protected function is_searchable($param)
	{
		return false;
	}
		
	/**
	 * Returns the minimum value that the field can have (for validation) 
	 */
	protected function min_val($param)
	{
		return null;
	}
		
	/**
	 * Returns the maximum value that the field can have (for validation)
	 */
	protected function max_val($param)
	{
		return null;
	}
}

