<?php
require_once 'Ds_Introspector.php';

/**
 * Data source introspector for MySQL
 */
class Ds_Introspector_Postgre extends Ds_Introspector
{
	function __construct() 
	{
	}

	function create_connection($url, $user, $password, $db_name)
	{
		$conn_string = "host=$url dbname=$db_name user=$user password=$password";
		$conn = pg_connect($conn_string);
		$this->set_connection($conn);
		$this->db_name = $db_name;
		
		if (!$conn) {
			throw new Exception('Coult no connect to PosgreSQL');
		}
	}
	
	/**
	 *
	 */
	function get_table_list($name_pattern = null)
	{
		$where_clause = " WHERE table_schema = 'public' AND table_catalog= '" . $this->db_name . "'" ;
		if (!empty($name_pattern)) {
			$where_clause .= ' AND table_name LIKE ' . $name_pattern;
		}
		
		//$sql_str = 'SHOW TABLES FROM ' . $this->db_name . $like_clause;	
		$sql_str = 
			'SELECT table_name, COUNT(*) AS column_count 
			 FROM INFORMATION_SCHEMA.COLUMNS ' . $where_clause . ' GROUP BY table_name';
		$db_result = pg_exec($this->connection, $sql_str);
		
		$retval = null;
		if ($db_result) {
			$retval = array();
			while($row = pg_fetch_array($db_result, NULL, PGSQL_ASSOC) ){ 
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
		$sql = 'SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name=\'' . $table_name . '\'';
		$db_result = pg_exec($this->connection, $sql);
		
		$schema = null;
		if ($db_result) {
			$schema = array(); // Only one structure per table
			$data_struct = $schema->create_entity($table_name);
			while($field = pg_fetch_array($db_result, NULL, PGSQL_ASSOC)) { 
				$field_info = &$data_struct->add_field_description($field['column_name']
						, $this->get_type_mapping($field['udt_name'])
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
			/*
			$db_result = $this->connection->query('SHOW INDEX FROM  ' . $table_name);
			if ($db_result) {
				while($row = $db_result->fetch_assoc()){ 
					$unique  = ($row['Non_unique'] == 0) ? true : false;
					$data_struct->add_index($row['Column_name'], $row['Key_name'], $unique);
				} 
			}*/
		}

		return $schema;
	}

	
	
	/**
	 * Returns true if is nullable - opposite of (not-null)
	 */
	protected function is_nullable($param)
	{
		return ($param['is_nullable'] == 'true');
	}
	
	/**
	 * Returns true if is the field is a key
	 */
	protected function is_key($param)
	{
		return false;
	}
	
	/**
	 * Returns the default value if defined, null otherwise
	 */
	protected function default_val($param)
	{
		return $param['column_default'];
	}
	
	/**
	 * Returns true if is an identity (e.g. auto_increment)
	 */
	protected function is_identity($param)
	{
		return (to_bool($param['is_identity']));
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
		if (strpos(strtolower($param['udt_name']), 'varchar') === 0 ) {
			$number = intval($param['character_maximum_length']); // in between parenthesis
			return $number;
		}
		return null;
	}
	
	/**
	 * Returns true if the field is updatable (i.e. non read-only) 
	 */
	protected function is_updatable($param)
	{
		return (to_bool($param['is_updatable']));
	}
	
	/**
	 * Returns the if the field is insertable (i.e. non system set) 
	 */
	protected function is_insertable($param)
	{
		return true;
	}
	
	
}
