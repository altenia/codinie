<?php echo "<?php"; ?>
/**
 * This file contains the <?php echo ucfirst($structure->name); ?> class definition
 *
 * PHP version 5
 *
 * @author    YourName <yourmail@wayfair.com>
 * @copyright 2013 Wayfair LLC - All rights reserved
 * @version   SVN: $Id$
 */

require INCLUDE_PATH . '/includes/clients/<?php echo $structure->name; ?>/<?php echo $structure->name; ?>_client_class.php';
 
/**
 * The model class that access the table <?php echo $structure->name; ?>
 */
class <?php echo ucfirst($structure->name); ?> {

<?php foreach ($structure->field_descriptions as $field_descr) { ?>
  /** The field of type <?php echo $field_descr->type; ?> **/
  public $<?php echo camel_to_underscore($field_descr->name); ?>;
<?php } ?>

  public $errors = array();

  public function __construct($id = null) {
    // Your constructor logic here
  }

  /**
   * Returns the unique primary key value
   * 
   * @return mixed the primary key that uniquely defines record in this model
   */
  public function get_pk()
  {
    return $this->[TODO:your_pk_here];
  }
  
  public function set_pk($val)
  {
    $this->[TODO:your_pk_here] = $val;
  }
  
  public static validate() {
<?php 
foreach ($structure->field_descriptions as $field_descr) { 
   if (!$field_descr->nullable) { ?>

      if (!empty($this-><?php echo camel_to_underscore($field_descr->name); ?>)) {
        $this->errors['missing_<?php echo camel_to_underscore($field_descr->name); ?>'] = lnrs('Missing<?php echo $field_descr->name; ?>', '', 'Please enter <?php echo $field_descr->name; ?>.');
      }
<?php } } ?>
  }
  
  public function load($load_type = 'basic') {
    $data = CustomerClient::get_customer($this->cu_id, $this->email_address, $this->us_web_cookie, $this->shard);
    
<?php foreach ($structure->field_descriptions as $field_descr) { ?>
      $this-><?php echo camel_to_underscore($field_descr->name); ?> = $data['<?php echo camel_to_underscore($field_descr->name); ?>'];
<?php } ?>
  }
  
  /**
   * Updates or inserts a <?php echo $structure->name; ?> record. it'll only create new if $this->cu_id is empty
   *
   * @param int $save_method The source from which they saved their data. This is currently only used during creation for reporting purposes.
   * @param int $save_us_id  UsID of the user doing the saving. This is just recorded as "this person triggered the save".
   *
   * @return bool on success
   */
  public function save($save_method = null) {
    
    // Make sure the object's ready for saving
    if (!$this->validate()) {
      return false;
    }
    
    $fields = array(
<?php foreach ($structure->field_descriptions as $field_descr) { ?>
      '<?php echo camel_to_underscore($field_descr->name); ?>' => $this-><?php echo camel_to_underscore($field_descr->name); ?>,
<?php } ?>
    );
    
    // If there is a primary key, then this is an update
    if (!empty($this->get_pk())) {
      $updated = <?php echo camel_to_underscore($field_descr->name); ?>Client::update($this->get_pk(), $fields);

      // If the update failed, error
      if (!$updated) {
        return false;
      }

    // Otherwise, if there *isn't* a primary key, we're creating a new record
    } else {
      // Create the customer
      $created = <?php echo remove_prefix($structure->name, 'tbl'); ?>Client::create($fields, CNSESSOID, $save_method, $this->errors);

      // If the creation failed, error
      if (!$created) {
        return false;
      }

      $this->set_pk($created['[TODO:your_pk_here]']);
    }

    return true;
  }

  
  /**
   * Delete a record and all info from the system.
   *
   * @return nothing
   */
  public function delete() {
    if (empty($this->get_pk())) {
      return null;
    }

    // Do actual customer delete
    <?php echo remove_prefix($structure->name, 'tbl'); ?>Client::delete($this->get_pk());

    // Clean data out of object
    foreach ($this as $var => $val) {
      $this->{$var} = null;
    }
  }
}