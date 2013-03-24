<?php 
$submit_label = 'Update';
if ($this->is_new) { 
	$submit_label = 'Create';
?>
<h3>Creating New</h3>
<?php } else { ?>
<h3>Editing [<?php echo $this->template['id']; ?>]</h3>
<?php } ?>

<form name="FormTemplate" action="<?php echo route_url($this, 'Admin', 'template_form'); ?>" method="post">
<?php if ($this->is_new) { ?>
	Template ID: <input type="text" name="template_id" value="" /> 
<?php } else { ?>
	<input type="hidden" name="template_id" value="<?php echo $this->template['id']; ?>" /> 
<?php } ?>
	<textarea name="template_info" style="width: 100%;" rows="10" ><?php echo $this->template['info']; ?></textarea>
	<textarea name="template_content" style="width: 100%;" rows="20" ><?php echo $this->template['content']; ?></textarea>
	<br />
	<input name="submit" type="submit" value="<?php echo $submit_label; ?>" />
</form>
