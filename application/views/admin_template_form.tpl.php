<?php 
$submit_label = 'Update';
if ($this->is_new) { 
	$submit_label = 'Create';
?>
<h3>Creating New</h3>
<?php } else { ?>
<h3>Editing [<?php echo $this->template_details['id']; ?>]</h3>
<?php } ?>

<form name="FormTemplate" action="<?php echo route_url($this, 'Admin', 'template_form'); ?>" method="post">
<?php if ($this->is_new) { ?>Template ID: <input type="text" name="template_id" value="" /> 
<?php } else { ?>
	<input type="hidden" name="template_id" value="<?php echo $this->template_details['id']; ?>" /> 
<?php } ?>
	<textarea name="template_info" style="width: 100%;" rows="8" ><?php echo $this->template_details['info_raw']; ?></textarea>
	<div class="">
		Available elements:<br>
		<ul>
			<li>$schema->{<a class="code_ref" href="#" data-code="$schema->name" >name</a>:string, <a class="code_ref" href="#" data-code="$schema->entities" >entities</a>:array<entity>, <a class="code_ref" href="#" data-code="$schema->namespace" >namespace</a>:string}</li>
			<li>$entity->{<a class="code_ref" href="#" data-code="$entity->name" >name</a>:string, <a class="code_ref" href="#" data-code="$entity->field_descriptions" >field_descriptions</a>:array, <a class="code_ref" href="#" data-code="$entity->get_fqn()" >get_fqn()</a>:string, <a class="code_ref" href="#" data-code="$entity->get_fqns(bool)" >get_fqns(bool)</a>:string}</li>
			<li>$feld_description->{<a class="code_ref" href="#" data-code="$feld_description->name" >name</a>:string, type:string, is_nullable:bool, is_key:bool, default_val:mixed, is_unique:bool, max_length:int, min_length:int, searchable:bool}</li>
		</ul>
	</div>
	<textarea id="template_content" name="template_content" style="width: 100%;" rows="20" ><?php echo $this->template_details['content']; ?></textarea>
	<br />
	<input name="submit" type="submit" value="<?php echo $submit_label; ?>" />
</form>
