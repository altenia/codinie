<?php

/**
 * The associative array that holds inofrmation about the field
 */
class FieldInfo extends ArrayObject
{
	/*public $name;
	public $type;
	public $is_nullable;
	public $is_key;
	public $default_val;
	public $is_unique;
	public $min_length;
	public $max_length;
	public $searchable;
	public $min_val;
	public $max_val;*/
	
	function __construct(){ 
        parent::__construct(array(), ArrayObject::ARRAY_AS_PROPS); 
    } 
}

/**
 * The DataStructure represents an entity (e.g. a table in a database).
 * A sequence of field descriptions.
 * A set of entities is refered as schema.
 */
class DataStructure 
{
	/** name of the package (e.g. namespace). Overrides the Schema's package **/
	public $package;
	
	/** name of the structure (table name) **/
	public $name;
	
	/** Array of field descriptions **/
	public $field_descriptions = array();
	
	/** Hashmap field name to index **/
	private $field_name_to_index = array();
	
	function __construct($name){
		$this->name = $name;
	}
	
	function &add_field_description($name, $type, $is_nullable, $is_key, $default_val)
	{
		$field_info = new FieldInfo();
		$field_info->name = $name;
		$field_info->type = $type;
		$field_info->is_nullable = $is_nullable;
		$field_info->is_key = $is_key;
		$field_info->default_val = $default_val;
		
		$this->field_name_to_index[$name] = sizeof($this->field_descriptions);
		$this->field_descriptions[] = $field_info;
		return $field_info;
	}

	/**
	 * Returns the associative array that represents the field attributes
	 * @param mixed $field_key Either the field name or the zero-based numeric index of the
	 */
	function &get_field_attributes($field_key)
	{
		$ordinal_idx = $field_key;
		if (!is_numeric($field_key)) {
			$ordinal_idx = $this->field_name_to_index[$field_key];
		}
		$field_info = $this->field_descriptions[$ordinal_idx];
		
		return $field_info;
	}
	
	/**
	 * Sets a value to the specified attribute
	 * @param mixed $field_key Either the field name or the zero-based numeric index of the
	 */
	function set_field_attribute($field_key, $attr_name, $attr_value)
	{
		$field_info = $this->get_field_attributes($field_key);
		$field_info[$attr_name] = $attr_value;
	}
	
	/**
	 * Adds a new indexing info to the field
	 */
	function add_index($field_key, $index_name, $unique)
	{
		$field_attributes = $this->get_field_attributes($field_key);
		if (!array_key_exists('indexes', $field_attributes)) {
			$field_attributes['indexes'] = array();
		}
		$field_attributes['indexes'][] = $index_name;
		$field_attributes['is_unique'] = $unique;
	}
}

/**
 * Clas that represents a set of entities (instances of data structures)
 */
class DataSchema 
{
	/** name of the package (e.g. namespace) **/
	public $package;

	/** name of the schema (e.g. database name) **/
	public $name;
	
	/** associative array of instances of DataStructure **/
	public $entities = array();
	
	function __construct($name){
		$this->name = $name;
	}
	
	/** 
	 * Creates a new entity and returns its reference
	 */
	function &create_entity($entity_name)
	{
		$entity = new DataStructure($entity_name);
		$this->entities[$entity_name] = $entity;
		return $entity;
	}
	
	/**
	 * Returns the entity
	 */
	function &get_entity($entity_name)
	{
		return array_key_exists($name, $this->entities) ? $this->entities[$name] : null;
	}
}
	