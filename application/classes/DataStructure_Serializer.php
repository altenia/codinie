<?php

abstract class DataStructure_Serializer {
	
	/**
	 * Returns a serialized
	 */
	abstract function serialize($schema);
}

class DataStructure_Serializer_Xml extends DataStructure_Serializer{
	
	/**
	 * Returns a serialized in string
	 */
	function serialize($schema)
	{
		$retval = '';
		foreach($schema as $data_structure) {
			$retval .= "<entity name=\"" . $data_structure->name . "\" >\n";
			foreach($data_structure->field_descriptions as $field_description) {
				$retval .= "\t<field name=\"" . $field_description->name 
					. "\" type=\"" . $field_description->type . "\"";
				if(isset($field_description->is_key)) {
					$retval .= " is_key=\"" . $field_description->is_key . "\"";
				}
				if(isset($field_description->is_unique)) {
					$retval .= " is_unique=\"" . $field_description->is_unique . "\"";
				}
				if(isset($field_description->default_val)) {
					$retval .= " default_val=\"" . $field_description->default_val . "\"";
				}
				if(isset($field_description->is_nullable)) {
					$retval .= " is_nullable=\"" . $field_description->is_nullable . "\"";
				}
				
				$retval .= " />\n";
				$field_description;
			}
			$retval .= "</entity>\n";
		}
		return $retval;
	}
}