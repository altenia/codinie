<?php echo "<?php"; ?>
/**
 * This file is part of <?php echo $params['name']; ?>.
 *
 * @author    <?php echo $params['owner']; ?> <yourmail@wayfair.com>
 * @version   0.1
 */
<?php foreach($schema as $structure) { ?> 
/**
 * The model class that access the table <?php echo $structure->name; ?> 
 */
class <?php echo ucfirst($structure->name); ?> {

<?php foreach ($structure->field_descriptions as $field_descr) { ?>
	/** The field of type <?php echo $field_descr->type; ?> **/
	public $<?php echo camel_to_underscore($field_descr->name); ?>;
<?php } ?>

	/**
	 * Validates 
	 */
	public static validate() {
	}

	/**
	* Load a record from persistent store
	*/
	public static load(<?php echo list_pks_csv($structure, '$'); ?>) {
	}
}
<?php } // foreach($schema as $structure) ?>