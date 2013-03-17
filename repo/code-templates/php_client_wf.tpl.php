<?php echo "<?php"; ?>

<?php foreach($schema as $structure) { ?>
/**
 * This file contains the <?php echo ucfirst($structure->name); ?> Client Class, which allows to switch between web services and DB calls
 *
 * PHP version 5
 *
 * @author    YourName <yourmail@wayfair.com>
 * @copyright 2013 Wayfair LLC - All rights reserved
 * @version   SVN: $Id$
 */
require INCLUDE_PATH . '/includes/clients/<?php echo $structure->name; ?>/<?php echo remove_prefix($structure->name, 'tbl'); ?>_client_database_class.php';
require INCLUDE_PATH . '/includes/clients/<?php echo $structure->name; ?>/<?php echo remove_prefix($structure->name, 'tbl'); ?>_client_webservice_class.php';

define ('FEATURE_ID', [TODO:feature_id]);

/**
 * The model class that access the table <?php echo $structure->name; ?>
 */
class <?php echo remove_prefix($structure->name, 'tbl'); ?>Client {

  public function __construct($id = null) {
    // Your constructor logic here
  }

  /**
   * Retrieves a <?php echo remove_prefix($structure->name, 'tbl'); ?> information
   *
   * @param int    $cu_id         Customer ID
   * @param string $email_address Email address
   * @param guid   $us_web_cookie User cookie guid
   * @param int    $shard         Shard on which the user is located
   *
   * @return array User's basic data
   */
  public static function get_<?php echo remove_prefix($structure->name, 'tbl', 'l'); ?>($cu_id = null) {
    if (feature_check(FEATURE_ID, 0, 'Webservice client - <?php echo remove_prefix($structure->name, 'tbl', 'uf'); ?>')) {
      $record = CustomerClient_Webservice::get_customer($cu_id, $email_address, $us_web_cookie, $shard);
    } else {
      $record = CustomerClient_Database::get_customer($cu_id, $email_address, $us_web_cookie, $shard);
    }

    return $record;
  }
  
  /**
   * Creates a new <?php echo remove_prefix($structure->name, 'tbl', 'l'); ?> record in <?php echo remove_prefix($structure->name, 'tbl'); ?>. 
   *
   * @param array $record_details <?php echo remove_prefix($structure->name, 'tbl'); ?> data to save. 
   * @param int   $store_id         ID of the store on which the user was created
   * @param int   $save_method      Source of the save
   * @param array &$errors          Errors that happened during the save
   *
   * @return array|bool If the save was successful, an array of some of the customer's data will be returned. If the
   *                    save failed, a boolean false is returned.
   */
  public static function create($record_details, $store_id, &$errors = array()) {
    if (feature_check(FEATURE_ID, 0, 'Webservice client - Customer')) {
      $created = CustomerClient_Webservice::create($customer_details, $store_id, $errors);
    } else {
      $created = CustomerClient_Database::create($customer_details, $store_id, $errors);
    }

    return $created;
  }
  
  /**
   * Updates an existing <?php echo remove_prefix($structure->name, 'tbl', 'l'); ?> record in <?php echo remove_prefix($structure->name, 'tbl'); ?>.
   *
   * @param int   $pk             ID of the record being updated
   * @param array $record_details <?php echo remove_prefix($structure->name, 'tbl'); ?> data to save. Keys are:
   * @param array &$errors        Errors that happened during the save
   * @param int   $shard          Database shard on which the record exists
   *
   * @return bool True if the save was successful, false otherwise
   */
  public static function update($cu_id, $record_details, &$errors = array(), $shard = null) {
    if (feature_check(FEATURE_ID, 0, 'Webservice client - <?php echo remove_prefix($structure->name, 'tbl', 'uf'); ?>')) {
      $updated = CustomerClient_Webservice::update($cu_id, $record_details, $save_us_id, $shard);
    } else {
      $updated = CustomerClient_Database::update($cu_id, $record_details, $shard);
    }

    return $updated;
  }
  
  /**
   * Deletes a <?php echo remove_prefix($structure->name, 'tbl', 'l'); ?> record
   *
   * @param int $pk <?php echo remove_prefix($structure->name, 'tbl', 'uf'); ?>'s id
   *
   * @return bool query success
   */
  public static function delete($pk) {
    if (feature_check(FEATURE_ID, 0, 'Webservice client - <?php echo remove_prefix($structure->name, 'tbl', 'uf'); ?>')) {
      $deleted = CustomerClient_Webservice::delete($pk);
    } else {
      $deleted = CustomerClient_Database::delete($pk);
    }

    return $deleted;
  }
}
<?php } // foreach($schema as $structure) ?>