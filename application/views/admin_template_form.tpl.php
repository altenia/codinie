<h3>Editing <?php echo $this->template_name; ?></h3>

<?php if(!empty($this->template_content)) { ?>
<form name="FormTemplate" action="<?php echo route_url($this, 'Admin', 'template_form'); ?>" method="post">
	<input type="hidden" name="template_name" value="<?php echo $this->template_name; ?>" /> 
	<textarea name="" class="input-xxlarge" rows="20" >
<?php echo $this->template_content; ?>
	</textarea>
	<br />
	<input name="submit" type="submit" value="Update" />
</form>
<?php } ?>