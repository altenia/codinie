<?php

require_once CLASSES_PATH . 'Ds_Introspector.php';

require_once INCLUDE_PATH . '/includes/classes/db_class.php';
require_once INCLUDE_PATH . '/includes/utility_functions.php';
require_once INCLUDE_PATH . '/includes/error_handlers.php';
require_once INCLUDE_PATH . '/includes/classes/mystats_class.php';
require_once INCLUDE_PATH . '/includes/classes/cache_class.php';
require_once INCLUDE_PATH . '/includes/language_functions.php';
require_once INCLUDE_PATH . '/includes/validation_functions.php';
require_once INCLUDE_PATH . '/includes/session_functions.php';
require_once INCLUDE_PATH . '/includes/classes/customer_class.php';
require_once INCLUDE_PATH . '/includes/autoloader.php';

/**
 *
 */
class Ds_Introspector_MsSqlWf extends Ds_Introspector
{
	function __construct() 
	{
	}

	function create_connection($url, $user, $password, $db_name)
	{
    $conn = DB::get_connection($url);
		$this->set_connection($conn);
		$this->db_name = $db_name;
		
		if ($conn === FALSE) {
			throw new Exception('Unable to connect to db ' . $db_name);
		}
	}
	
	/**
	 *
	 */
	function get_table_list($name_pattern = null)
	{
		$where_clause = '';
		if (!empty($name_pattern)) {
			$where_clause = ' where name LIKE \'' . $name_pattern . '\'';
		}
		
		$sql_str = 
			'SELECT name as table_name, max_column_id_used as column_count 
         FROM sys.Tables ' . $where_clause . ' ORDER BY table_name ASC';	
		$db_result = $this->connection->query_to_array($sql_str);
    
    
		return $db_result;
	}
	
	/**
	 * Returns the table metadata in form of associative array
	 * The row entry is of form: {field_name, type, length, nullable, key, default, extra
	 */
	function get_schema($table_name)
	{
		$sql_str = 'SELECT * FROM information_schema.columns 
			WHERE table_name = \'' . $table_name . '\'
			ORDER BY ordinal_position';
		$db_result = $this->connection->query_to_array($sql_str);
  
		$retval = null;
		if ($db_result) {
			$retval = array(); // Only one structure per table
			$data_struct = new DataStructure($table_name);
			foreach($db_result as $field) { 
				$field_info = &$data_struct->add_field_description($field['COLUMN_NAME']
						, $this->get_type_mapping($field['DATA_TYPE'])
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
			$retval[] = $data_struct;
		}
    
    // Handle primary keys
    $sql_str = 'SELECT column_name 
      FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
      WHERE table_name = \'' . $table_name . '\'';
		$db_result = $this->connection->query_to_array($sql_str);
    // The result is a list of key columns
    
    if ($db_result) {
      foreach($db_result as $field_name) { 
        //print_r($field_name['column_name']); die();
        $retval[0]->set_field_attribute($field_name['column_name'], 'is_key', true);
      }
    }
		return $retval;
	}
	
  /////////
  /**
	 * Returns true if is nullable - opposite of (not-null)
	 */
	protected function is_nullable($param)
	{
		if ($param['IS_NULLABLE'] == 'YES')
      return true;
		return false;
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
		return $param['COLUMN_DEFAULT'];
	}
	
	/**
	 * Returns true if is an identity (e.g. auto_increment)
	 */
	protected function is_identity($param)
	{
		return false;
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
		if (empty($param['CHARACTER_MAXIMUM_LENGTH'])) {
			$number = intval($param['CHARACTER_MAXIMUM_LENGTH']); 
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
