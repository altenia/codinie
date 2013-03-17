<?php echo "<?php"; ?>

<?php foreach($schema as $structure) { ?>
/**
 * This file contains the <?php echo $structure->name; ?> Client DB Class, which separates the layer of DB centric logic from the <?php echo remove_prefix($structure->name, 'tbl'); ?> object.
 *
 * @author    YourName <yourmail@mail.com>
 * @version   SVN: $Id$
 */

/**
 * The model class that access the table <?php echo $structure->name; ?>
 */
class <?php echo $structure->name; ?>_Dao {

  public function __construct($id = null) {
    // Your constructor logic here
  }

  /**
   * Retrieves a <?php echo $structure->name; ?> with basic information
   *
   * @return array <?php echo $structure->name; ?>'s basic data
   */
  public function get_<?php echo $structure->name; ?>($pk = null) 
  {
	// If there are no search terms provided, we can't go much farther
	if (empty($pk)) {
		return false;
	}
    
    $sql =
      ' SELECT    
<?php foreach ($structure->field_descriptions as $field_descr) { ?>
                  <?php echo $field_descr->name; ?> AS <?php echo camel_to_underscore($field_descr->name); ?> 
<?php } ?> 

        FROM      <?php echo $structure->name; ?> WITH (NOLOCK)
        WHERE     <?php echo $structure->name; ?>.[TODO:pk_col_name] = ' . quote($pk) ;

  }
  
 
  /**
   * Updates an existing <?php echo $structure->name; ?> record in the table
   *
   */
  public static function update($pk, $record_details, &$errors = array(), $shard = null) {
	if (empty($record_details) || !is_array($record_details)) {
		return false;
	}

    $sql =
      ' UPDATE  <?php echo $structure->name; ?>
        SET     
<?php 
$ctr = 1;
$last = sizeof($structure->field_descriptions);
foreach ($structure->field_descriptions as $field_descr) { 
  $ctr++;
  ?>
                <?php echo $field_descr->name; ?> = ' .  quote($record_details['<?php echo camel_to_underscore($field_descr->name); ?>']) . ' <?php if ($ctr <= $last) echo ', '; ?> 
<?php } ?> 
        WHERE   <?php echo $structure->name; ?>.CuID = ' . quote($pk, 1);

    $dbct->query($sql);
    return true;
  }
}
<?php } // foreach($schema as $structure) ?>