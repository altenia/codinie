
<form class="form-horizontal" name="Project" action="<?php echo route_url($this, 'Project', 'form'); ?>" method="post">
<?php
	$readonly_attr = ($this->is_new) ? '' : 'readonly="readonly"' ;
	$submit_label = ($this->is_new) ? 'Create' : 'Update' ;
?>
	<h3>Project Details</h3>
	
	<h4>Basic Information</h4>
	<div class="control-group">
		<label class="control-label" for="project.id">Project ID</label>
		<div class="controls">
			<input type="text" name="project.id" <?php echo $readonly_attr; ?> value="<?php echo ifndef('id', $this->project_details, ''); ?>">
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="project.name">Project Name</label>
		<div class="controls">
			<input type="text" name="project.name" value="<?php echo ifndef('name', $this->project_details, ''); ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="project.owner">Owner</label>
		<div class="controls">
			<input type="text" name="project.owner" value="<?php echo ifndef('owner', $this->project_details, ''); ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="project.description">Description</label>
		<div class="controls">
			<textarea type="text" name="project.description" ><?php echo ifndef('description', $this->project_details, ''); ?></textarea>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="project.language">Language</label>
		<div class="controls">
			<input type="text" name="project.language" value="<?php echo ifndef('language', $this->project_details, ''); ?>">
		</div>
	</div>

	<hr />
	<h4>Data Source</h4>
	<div class="control-group">
		<label class="control-label" for="project.data-source.type">DS Type</label>
		<div class="controls">
			<?php echo generate_select_html('project.data-source.type', $this->ref_ds_types, 0, 0, ifndef('type', $this->project_details['data-source'], '')); ?>
		</div>
	</div>
		
	<div class="control-group">
		<label class="control-label" for="project.data-source.url">URL</label>
		<div class="controls">
			<input type="text" name="project.data-source.url" value="<?php echo ifndef('url', $this->project_details['data-source'], 'localhost'); ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="project.data-source.username">User</label>
		<div class="controls">
			<input type="text" name="project.data-source.username" value="<?php echo ifndef('username', $this->project_details['data-source'], 'test'); ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="project.data-source.db_name">Db Name</label>
		<div class="controls">
			<input type="text" name="project.data-source.db_name" value="<?php echo ifndef('db_name', $this->project_details['data-source'], 'test'); ?>">
		</div>
	</div>
	
	<hr />
	<h4>Templates</h4>
	<div class="control-group">
		<div class="controls">
<?php foreach($this->templates as $template) { 
	$checked_attr = array_key_exists_md( array('active-templates', $template['id']), $this->project_details) ? 'checked="checked"' : '';
?>
			<label class="checkbox">
				<input type="checkbox" name="project.active-template[]" value="<?php echo $template['id']; ?>" <?php echo $checked_attr; ?> >
				<?php echo $template['id']; ?>
			</label>
<?php } ?>
		</div>
	</div>
	<hr />
	<h4>Parameters</h4>
	
	<div class="control-group">
		<label class="control-label" for="project.params">Parameters</label>
		<div class="controls">
			<textarea name="project.params" ><?php if (array_key_exists('params', $this->project_details)) { 
				echo array_to_string( $this->project_details['params']); 
			} ?></textarea>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="submit" name="submit" value="<?php echo $submit_label ; ?>">
		</div>
	</div>
</form>
