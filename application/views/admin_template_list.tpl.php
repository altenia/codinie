<h3>Templates</h3>

<form name="ListTemplates" action="<?php echo route_url($this, 'Admin', 'template_list'); ?>" method="get">
<input type="text" name="pattern" value="<?php echo $this->pattern; ?>" /> <input name="submit" type="submit" value="Filter" />
</form>
<?php if(!empty($this->templates)) { ?>
<table class="table">
	<thead>
		<tr>
			<td>Template</td>
			<td>Description</td>
		</tr>
	</thead>
	<tbody>
<?php foreach($this->templates as $template) { ?>
		<tr>
			<td><a href="<?php echo route_url($this, 'Admin', 'template_form', array('name' => $template['name'])); ?>"><?php echo $template['name']; ?></a></td>
			<td></td>
		</tr>
<?php } ?>
    <tr>
	</tbody>
</table>
</form>
<?php } ?>


<?php if(!empty($this->template_content)) { ?>
<form name="FormTemplate" action="<?php echo route_url($this, 'Admin', 'template_form'); ?>" method="post">
	<input type="hidden" name="template_name" value="<?php echo $this->template_name; ?>" /> 
	<textarea name="" class="input-xxlarge" rows="20" >
<?php echo $this->template_content; ?>
	</textarea>
	<input name="submit" type="submit" value="Filter" />
</form>
<?php } ?>