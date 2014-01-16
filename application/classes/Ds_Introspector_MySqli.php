<?php
require_once 'Ds_Introspector.php';

/**
 * Data source introspector for MySQL
 */
class Ds_Introspector_MySqli extends Ds_Introspector
{
	public $db_name;
	
	function __construct() 
	{
	}

	function create_connection($url, $user, $password, $db_name)
	{
		$conn = new mysqli($url, $user, $password, $db_name);
		$this->set_connection($conn);
		$this->db_name = $db_name;
		
		if ($conn->connect_errno) {
			throw new Exception($conn->connect_error);
		}
	}
	
	/**
	 *
	 */
	function get_table_list($name_pattern = null)
	{
		$where_clause = " WHERE table_schema = '" . $this->db_name . "'" ;
		if (!empty($name_pattern)) {
			$where_clause .= ' AND table_name LIKE ' . $name_pattern;
		}
		
		//$sql_str = 'SHOW TABLES FROM ' . $this->db_name . $like_clause;	
		$sql_str = 
			'SELECT table_name, COUNT(*) AS column_count 
			FROM INFORMATION_SCHEMA.COLUMNS ' . $where_clause . ' GROUP BY table_name';
		$db_result = $this->connection->query($sql_str);
		
		$retval = null;
		if ($db_result) {
			$retval = array();
			while($row = $db_result->fetch_array()){ 
				$retval[] = $row; 
			} 
		}
		return $retval;
	}
	
	/**
	 * Returns the schema which contains a list of table definitions
	 * @todo - change from single tablename to array of tablenames
	 * @param  array $table_names list of tablenames to include in the schema
	 * @return DataSchema              [description]
	 */
	function get_schema($table_name)
	{
		$db_result = $this->connection->query('DESC  ' . $table_name);
		
		$schema = null;
		if ($db_result) {
			$schema = new DataSchema($this->db_name);
			$data_struct = $schema->create_entity($table_name);
			while($field = $db_result->fetch_assoc()) { 
				$field_info = &$data_struct->add_field_description($field['Field']
						, $this->get_type_mapping($field['Type'])
						, $this->is_nullable($field), $this->is_key($field), $this->default_val($field)
					);
				$field_info->is_identity = $this->is_identity($field);
				$field_info->is_unique = $this->is_unique($field);
				$field_info->min_length = $this->min_length($field);
				$field_info->max_length = $this->max_length($field);

				$field_info->is_updatable = $this->is_updatable($field);
				$field_info->is_insertable = $this->is_insertable($field);

				$field_info->is_searchable = $this->is_searchable($field);
				$field_info->min_val = $this->min_val($field);
				$field_info->max_val = $this->max_val($field);
			} 
			
			// Introspecting indexes
			$db_result = $this->connection->query('SHOW INDEX FROM  ' . $table_name);
			if ($db_result) {
				while($row = $db_result->fetch_assoc()){ 
					$unique  = ($row['Non_unique'] == 0) ? true : false;
					$data_struct->add_index($row['Column_name'], $row['Key_name'], $unique);
				} 
			}
		}

		return $schema;
	}

	
	
	/**
	 * Returns true if is nullable - opposite of (not-null)
	 */
	protected function is_nullable($param)
	{
		return ($param['Null'] == 'true');
	}
	
	/**
	 * Returns true if is the field is a key
	 */
	protected function is_key($param)
	{
		return ($param['Key'] == 'true');
	}
	
	/**
	 * Returns the default value if defined, null otherwise
	 */
	protected function default_val($param)
	{
		return $param['Default'];
	}
	
	/**
	 * Returns true if is an identity (e.g. auto_increment)
	 */
	protected function is_identity($param)
	{
		if (array_key_exists('Extra', $param) && strpos($param['Extra'], 'auto_increment') === FALSE)
			return false;
		return true;
	}
	
	/**
	 * Returns true if the column has unique constraint
	 */
	protected function is_unique($param)
	{
		return $this->is_key($param);
	}
	
	/**
	 * Returns the max length (by default is null) 
	 */
	protected function max_length($param)
	{
		if (strpos(strtolower($param['Type']), 'varchar') === 0 ) {
			$number = intval(substr($param['Type'],8, strlen($param['Type']) -9)); // in between parenthesis
			return $number;
		}
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
	
	
}
