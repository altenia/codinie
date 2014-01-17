<div style="float:right"><a href="<?php echo route_url($this->request_context, 'Project_Main', 'form', array('prjid' => $this->project_details['id'])); ?>">Edit project details</a></div>
<h3>Generate Code</h3>
<h4>Configure Data Schema source</h4>
<form class="form-horizontal" name="Connect" action="" method="post">

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
		<label class="control-label" for="password">Password</label>
		<div class="controls">
			<input type="text" name="password" value="">
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="project.data-source.db_name">Db Name</label>
		<div class="controls">
			<input type="text" name="project.data-source.db_name" value="<?php echo ifndef('db_name', $this->project_details['data-source'], 'test'); ?>">
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="table_pattern">table pattern</label>
		<div class="controls">
			<input type="text" name="table_pattern" value="">
		</div>
	</div>

	<div class="control-group">
		<div class="controls">
			<label class="checkbox">
				<input type="checkbox" name="project.active-template[]" value="a">
				Save configuration
			</label>
			<input type="submit" name="ListTables" value="List Tables"> <input type="submit" name="InputForm" value="Copy/Paste Form">
		</div>
	</div>
</form>

<hr />
<?php if(empty($this->tables)) { ?>
<strong>No tables were found</strong>
<?php } else  { ?>
<h4>Select Tables for the code generation</h4>
<form name="Generate" action="<?php echo route_url($this->request_context, 'Project_Main', 'generate_code'); ?>" method="post">
<input type="hidden" name="project.data-source.type" value="<?php echo $this->conn_details['ds_type']; ?>" />
<input type="hidden" name="project.data-source.url" value="<?php echo $this->conn_details['url']; ?>" />
<input type="hidden" name="project.data-source.username" value="<?php echo $this->conn_details['username']; ?>" />
<input type="hidden" name="password" value="<?php echo $this->conn_details['password']; ?>" />
<input type="hidden" name="project.data-source.db_name" value="<?php echo $this->conn_details['db_name']; ?>" />
<table class="table">
	<thead>
		<tr>
			<td><a id="toggle_select">Select</a></td>
			<td>Table name</td>
			<td>Num Columns</td>
		</tr>
	</thead>
	<tbody>
<?php foreach($this->tables as $table) { ?>
		<tr>
			<td><input name="tables[]" type="checkbox" value="<?php echo $table['table_name']; ?>" /></td>
			<td><?php echo $table['table_name']; ?></td>
			<td><?php echo $table['column_count']; ?></td>
		</tr>
<?php } ?>
    <tr>
			<td colspan="3"><input name="submit" type="submit" value="Generate" /></td>
		</tr>
	</tbody>
</table>
</form>
<script>
	$(document).ready(function(){
		var checked = false;
		$("#toggle_select").on("click", function(){
			checked = !checked;
			$("[name='tables[]']").prop('checked', checked);
		})
	})
</script>
<?php } ?>