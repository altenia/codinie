<?php
require_once 'Ds_Introspector.php';

/**
 * Data Schema Introspector's: Json Schmea file
 */
class Ds_Introspector_JsonSchema extends Ds_Introspector
{
	function __construct() 
	{
		$type_mapping = array(
			'boolean' => 'boolean', 
			'date' => 'date', 
			'datetime' => 'datetime', 
			'number' => 'double', 
			'float' => 'float', 
			'integer' => 'int', 
			'long' => 'long', 
			'short' => 'short',
			'string' => 'string', 
			'timestamp' => 'timestamp', 
			'clob' => 'clob', 
		);
		$this->set_type_mappings($type_mapping);
	}

	function create_connection($url, $user, $password, $schema_name)
	{
		if (!is_dir($url)) {
			throw new Exception('Invalid directory [' . $url . ']');
		}
		$this->url = $url;
		$this->db_name = $schema_name;
	}
	
	/**
	 *
	 */
	function get_table_list($name_pattern = null)
	{
		if (empty($name_pattern)) {
			$name_pattern = '*.schema.json';
		} else {
			$name_pattern .= $name_pattern . '.schema.json';
		}
		//print_r($this->url . $name_pattern);
		$files = glob($this->url . $name_pattern);
				
		$retval = null;
		if (!empty($files)) {
			$retval = array();
			foreach($files as $file){ 
				$retval[] = array( 'table_name' => substr($file, strlen($this->url), strlen($file) ), 'column_count' => '?'); 
			} 
		}
		return $retval;
	}
		
	/**
	 * Returns the table metadata in form of associative array
	 * The row entry is of form: {field_name, type, length, is_nullable, key, default, extra
	 */
	function get_schema($table_name)
	{
		$json_schema = new json_decode ($this->url . $table_name, true);
		
		if (empty($json_schema)) {
			return null;
		}

		// List of fields with unresolved references
		$unresolved_references = array();
		
		$schema = null;
		if ($json_schema) {
			$schema = new DataSchema($this->db_name);
			foreach ($json_schema as $entry_key => $entry_val) {
				switch ($entry_key) {
				case 'id':
					break;
				case '$schema':
					break;
				case 'title':
					$schema->name = $entry_val;
					break;
				case 'description':
					$schema->description = $entry_val;
					break;
				case 'type':
					if ($entry_val == 'object') {
						// Check for 'properties', and 'required'
					}
					break;
				}
				
			}
		}
		
		//print_r($unresolved_references);
		
		foreach($unresolved_references as $unresolved_ref)
		{
			$class_ref = $schema->get_entity($unresolved_ref->class_ref);
//print_r($unresolved_ref);
//print_r($class_ref);
			if ($class_ref != null) {
				// Class reference was found.
//print("ASSOC:".$unresolved_ref->class_ref." TO :".$class_ref->name."\n");
				$unresolved_ref->class_ref = $class_ref;
			}
		}

		return $schema;
	}
	
	/**
	 * Processes a (sub)schema
	 */
	private function process_subschema($json_schema, $parent, &$schema) {
		foreach ($json_schema as $entry_key => $entry_val) {
			switch ($entry_key) {
			case 'id':
				break;
			case '$schema':
				break;
			case 'title':
				$schema->name = $entry_val;
				break;
			case 'description':
				$schema->description = $entry_val;
				break;
			case 'default':
				break;
			}
			case 'default':
				break;
			}
		}
		$json_schema['id'];
		$json_schema['$schema'];
		$json_schema['title'];
		$json_schema['description'];
		$json_schema['default'];
		$json_schema['multipleOf'];
		$json_schema['maximum'];
		$json_schema['exclusiveMaximum'];
		$json_schema['minimum'];
		$json_schema['exclusiveMinimum'];
		$json_schema['maxLength'];
		$json_schema['minLength'];
		$json_schema['pattern'];
		$json_schema['additionalItems'];
		$json_schema['items'];
		$json_schema['maxItems'];
		$json_schema['minItems'];
		$json_schema['uniqueItems'];
		$json_schema['maxProperties'];
		$json_schema['minProperties'];
		$json_schema['required'];
		$json_schema['additionalProperties'];
		
		
		$json_schema['definitions'];
		$json_schema['patternProperties'];
		$json_schema['dependencies'];
		$json_schema['enum'];
		$json_schema['type'];
		$json_schema['allOf'];
		$json_schema['anyOf'];
		$json_schema['oneOf'];
		$json_schema['not'];
	}
	
	private function process_properties($json_properties) {
	
		foreach($json_schema->class as $class) { 
				$class_attrs = $class->attributes();
				$data_struct = $schema->create_entity((string)$class_attrs->name);
				$data_struct->store_name = (string)$class_attrs->table;
				//print_r($class_attrs->name); die();
				
				// Process the identity field
				foreach($class->id as $property) {
					$field_info = $this->process_field($data_struct, $property);
					$field_info->is_nullable = false;
					$field_info->is_key = true;
					$field_info->is_unique = true;
					if (isset($property->generator)) {
						$field_info->is_autoincrement = true;
					}
					$data_struct->set_identity_field($field_info);
				}
				
				// Process regular fields 
				foreach($class->property as $property) {
					$this->process_field($data_struct, $property);
				}
				
				// Process many-to-one fields
				foreach($class->{'many-to-one'} as $property) {
					$field_info = $this->process_field($data_struct, $property);
					$field_info->class_ref = (string)$this->obtain_field_attribute($property, 'class', null);
					$field_info->type= 'class'; 
					
					$class_ref = $schema->get_entity($field_info->class_ref);
					
					if ($class_ref != null) {
//print("ASSIGNED:".$class_ref->name."\n");
						// Class reference was found.
						$field_info->class_ref = $class_ref;
					} else {
//print("PENDING:".$field_info->name."\n");
						$unresolved_references[] = $field_info;
					}
					// In the scond pass, for all those not found, 
				}
			} 
	
	}
	
	/**
	 * Processes a field node (id|property|many-to-one)
	 */
	private function process_field($data_struct, $field)
	{
		$prop_attrs = $field->attributes();
		$field_info = &$data_struct->add_field_description( (string)$prop_attrs->name
				, (string)$this->get_type_mapping($prop_attrs->type, true)
				, $this->is_nullable($field), $this->is_key($field), (string)$this->default_val($field)
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
		return $field_info;
	}
		
	
	/**
	 * Returns true if is nullable - opposite of (not-null)
	 */
	protected function is_nullable($param)
	{
		$val = $this->obtain_field_attribute($param, 'true');
		return ($val != 'true');
	}
	
	/**
	 * Returns true if is the field is a key
	 */
	protected function is_key($param)
	{
		$val = $this->obtain_field_attribute($param, 'unique', 'false');
		return ($val == 'true');
	}
	
	/**
	 * Returns the default value if defined, null otherwise
	 */
	protected function default_val($param)
	{
		$val = $this->obtain_field_attribute($param, 'default');
		return $val;
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
		$val = $this->obtain_field_attribute($param, 'unique', 'false');
		return ($val == 'true');
	}
	
	/**
	 * Returns the max length (by default is null) 
	 */
	protected function max_length($param)
	{
		$val = $this->obtain_field_attribute($param, 'length');
		
		if ($val != null)
			return (int)$val;
		return null;
	}
	
	/**
	 * Returns true if the field is updatable (i.e. non read-only) 
	 */
	protected function is_updatable($param)
	{
		$val = $this->obtain_field_attribute($param, 'update', 'true');
		
		return ($val == 'true');
	}
	
	/**
	 * Returns the if the field is insertable (i.e. non system set) 
	 */
	protected function is_insertable($param)
	{
		$val = $this->obtain_field_attribute($param, 'insert', 'true');
		
		return ($val == 'true');
	}

	
	/**
	 * Returns the value of the attribute in the element passed as $param 
	 * or in the column element under the current element
	 */
	private function obtain_field_attribute($param, $attr_name, $def_val = null)
	{
		if (isset($param->attributes()->{$attr_name})) {
			return ( $param->attributes()->{$attr_name});
		} else if (isset($param->column)) {
			if (isset($param->column->attributes()->{$attr_name}))
				return ( $param->column->attributes()->{$attr_name});
		} 
		return $def_val;
	}
}
