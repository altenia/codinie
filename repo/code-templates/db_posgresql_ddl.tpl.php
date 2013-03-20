/**
 * This file is DDL for <?php echo $params['name']; ?>.
 *
 * @author    <?php echo $params['owner']; ?> <yourmail@email.com>
 * @version   0.1
 */
<?php foreach($schema as $structure) { ?> 
CREATE TABLE <?php echo $structure->name; ?> (
<?php 
$ctr = 0;
$count = sizeof($structure->field_descriptions);
foreach ($structure->field_descriptions as $field_descr) {
	$ctr++; 
	echo "\t" . camel_to_underscore($field_descr->name) . ' ' . to_db_type($field_descr->type) . modifiers($field_descr);  
	if ($ctr < $count) 
		echo ',';
	echo "\n";
} 
?> 
)
<?php } // foreach($schema as $structure) ?>

<?php
// helper functions
function to_db_type($type)
{
	$type_mapping = array(
		'bigint' => 'BIGINT', 
		'binay' => 'BINAY', 
		'blob' => 'BLOB', 
		'boolean' => 'BOOLEAN', 
		'clob' => 'CLOB', 
		'char' => 'CHAR', 
		'date' => 'DATE', 
		'datetime' => 'DATETIME', 
		'decimal' => 'DECIMAL', 
		'double' => 'DOUBLE', 
		'enum' => 'ENUM', 
		'float' => 'FLOAT', 
		'int' => 'INT', 
		'long' => 'LONG', 
		'string' => 'VARCHAR', 
		'short' => 'SHORT', 
		'text' => 'TEXT', 
		'time' => 'TIME', 
		'timestamp' => 'TIMESTAMP', 
	);
	return array_key_exists($type, $type_mapping) ? $type_mapping[$type] : $type;
}

function modifiers($field_descr)
{
	$modifiers = '';
	if (!empty($field_descr->max_length)) {
		$modifiers = '(' . $field_descr->max_length . ')';
	}
	if (!$field_descr->is_nullable) {
		$modifiers .= ' NOT NULL';
	}
	if (to_bool($field_descr->is_identity)) {
		$modifiers .= ' AUTO_INCREMENT';
	}
	if (!empty($field_descr->default_val)) {
		$modifiers .= ' DEFAULT \'' . $field_descr->default_val . '\'';
	}
	return $modifiers;
}
?>