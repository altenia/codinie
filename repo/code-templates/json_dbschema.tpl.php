
<?php
// helper functions
if(!function_exists("_to_type")) {
	function _to_type($schema, $field_descr)
	{
		$type_mapping = array(
			
		);
		
		if ($field_descr->type == 'class') {
			if (is_string($field_descr->class_ref)) {
				return (string)$field_descr->class_ref;
			} else {
				$ref = $field_descr->class_ref;
				return array_key_exists($ref->identity_field->type, $type_mapping) ? $type_mapping[$ref->identity_field->type] : $ref->identity_field->type;
			}
		}
		return array_key_exists($field_descr->type, $type_mapping) ? $type_mapping[$field_descr->type] : $field_descr->type;
	}
}

if(!function_exists("_modifiers")) {
	function _modifiers($field_descr)
	{
		$modifiers = array();
		if (!empty($field_descr->max_length)) {
			$modifiers[] = '"length":' . $field_descr->max_length;
		}
		if (to_bool($field_descr->is_identity)) {
			$modifiers[] = '"is_identity": true';
		}
		if (!$field_descr->is_nullable) {
			$modifiers[] = '"is_nullable": true';
		}
		if (!empty($field_descr->default_val)) {
			$modifiers[] = '"default_val": "' . $field_descr->default_val . '"';
		}
		if (!empty($modifiers)) {
			// prepend and empty string to prepend a comma when imploded
			array_unshift($modifiers, '');
		}
		return implode(", ", $modifiers);
	}
}
?>
{
	"description": "Json db schema generated by Codini",
	"schema-name": "<?php echo $schema->name ?>",
	"date": "<?php $currdate = new DateTime(); echo $currdate->format(DateTime::ISO8601); ?>",

	"entities": {<?php 
		$entities_arr = array_values($schema->entities);
		$num_entities = count($entities_arr);
		for($entity_idx = 0; $entity_idx < $num_entities;  $entity_idx++) { 
			$entity = $entities_arr[$entity_idx]; ?> 
		"<?php echo $entity->name; ?>": {
			"fields": [
<?php 
$ctr = 0;
$count = sizeof($entity->field_descriptions);
foreach ($entity->field_descriptions as $field_descr) {
	$ctr++; 
	echo "\t\t\t".'{"name":"' . camel_to_underscore($field_descr->name) . '", "type":"' . _to_type($schema, $field_descr) .'"' . _modifiers($field_descr) . '}';  
	if ($ctr < $count) 
		echo ',';
	echo "\n";
} 
?> 
			]
		}<?php if ($entity_idx < $num_entities-1) echo ',' ?>

<?php } // foreach($schema as $entity) ?>
	}
}