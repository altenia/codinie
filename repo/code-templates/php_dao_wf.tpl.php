<?php echo "<?php"; ?>

<?php foreach($schema as $structure) { ?>
/**
 * This file contains the <?php echo remove_prefix($structure->name, 'tbl'); ?> Client DB Class, which separates the layer of DB centric logic from the <?php echo remove_prefix($structure->name, 'tbl'); ?> object
 *
 * PHP version 5
 *
 * @author    YourName <yourmail@wayfair.com>
 * @copyright 2013 Wayfair LLC - All rights reserved
 * @version   SVN: $Id$
 */

require INCLUDE_PATH . '/includes/clients/<?php echo $structure->name; ?>/<?php echo remove_prefix($structure->name, 'tbl'); ?>_client_database_class.php';
require INCLUDE_PATH . '/includes/clients/<?php echo $structure->name; ?>/<?php echo remove_prefix($structure->name, 'tbl'); ?>_client_webservice_class.php';
/**
 * The model class that access the table <?php echo $structure->name; ?>
 */
class <?php echo remove_prefix($structure->name, 'tbl'); ?>Client_Database {

  public function __construct($id = null) {
    // Your constructor logic here
  }

  /**
   * Retrieves a customer with basic information
   *
   * @param int    $cu_id         Customer ID
   * @param string $email_address Email address
   * @param guid   $us_web_cookie User cookie guid
   * @param int    $shard         Shard on which the user is located
   *
   * @return array Customer's basic data
   */
  public static function get_customer($pk = null) 
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
        WHERE     tblUser.[TODO:pk_col_name] = ' . quote($pk) ;
    $db_conn = DB::get_connection('[TODO:db]');
    $results = $db_conn->query_to_array($sql);

    // If we have results, return them (after appending the shard to the results)
    if (!empty($results)) {
      return $results[0];
    } else {
      return false;
    }
  }
  
  /**
   * Creates a new customer record in tblCustomer. Also validates for existing email, updates reporting on account creation
   *
   * @param array $record_details Customer data to save. Keys are:
   * @param int   $store_id         ID of the store on which the user was created
   * @param array &$errors          Errors that happened during the save
   *
   * @return array|bool If the save was successful, an array of some of the customer's data will be returned. If the
   *                    save failed, a boolean false is returned.
   */
  public static function create($record_details, $store_id, &$errors = array()) {
    $ret = array('us_id' => null);

    // Obviously we have to make sure they've got their data
    if (empty($record_details) || !is_array($record_details)) {
      $errors['missing_data'] = lnrs('NoCustomerDataProvided', '', 'The account cannot be saved because no customer data was provided.');
      return false;

    $dbct = DB::get_connection('[TODO:db]');

    $sql =
      ' TODO
      ';

    $results = $dbct->query_to_array($sql);

    // If the save failed, return the error
    if (empty($results)) {
      $errors['unknown_error'] = lnrs('CustomerCouldNotBeSaved', '', 'Account could not be saved due to an error.');
      return false;
    }
    return $ret;
  }
  
  /**
   * Updates an existing customer record in tblCustomer
   *
   * @param int   $cu_id            ID of the customer being updated
   * @param array $record_details Customer data to save. Keys are:
   * @param int   $save_us_id       UsID of the user doing the saving
   * @param array &$errors          Errors that happened during the save
   * @param int   $shard            Database shard on which the user exists
   *
   * @return bool True if the save was successful, false otherwise
   */
  public static function update($pk, $record_details, &$errors = array(), $shard = null) {
    if (empty($record_details) || !is_array($record_details)) {
      return false;
    }

    // Get a connection 
    $dbct = DB::get_connection('[TODO:db]');

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