
<form class="form-horizontal" name="Connect" action="" method="post">
	<div class="control-group">
		<label class="control-label" for="ds_type">DB Adapter</label>
			<div class="controls">
				<?php echo generate_select_html('ds_type', $this->ref_ds_types, 0, 0, ifndef('ds_type', $this->conn_details, '')); ?>
			</div>
	</div>
		
	<div class="control-group">
		<label class="control-label" for="url">URL</label>
		<div class="controls">
			<input type="text" name="url" value="<?php echo ifndef('url', $this->conn_details, 'localhost'); ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="user">User</label>
		<div class="controls">
			<input type="text" name="user" value="<?php echo ifndef('user', $this->conn_details, 'test'); ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="password">Password</label>
		<div class="controls">
			<input type="text" name="password" value="<?php echo ifndef('password', $this->conn_details, 'test'); ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="db_name">Db Name</label>
		<div class="controls">
			<input type="text" name="db_name" value="<?php echo ifndef('db_name', $this->conn_details, 'test'); ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="table_pattern">table pattern</label>
		<div class="controls">
			<input type="text" name="table_pattern" value="">
			<input type="submit" name="List tables">
		</div>
	</div>
</form>

<hr />
<?php if(!empty($this->tables)) { ?>
<form name="Generate" action="<?php echo route_url($this, 'Index', 'generate_code'); ?>" method="post">
<input type="hidden" name="ds_type" value="<?php echo $this->conn_details['ds_type']; ?>" />
<input type="hidden" name="url" value="<?php echo $this->conn_details['url']; ?>" />
<input type="hidden" name="user" value="<?php echo $this->conn_details['user']; ?>" />
<input type="hidden" name="password" value="<?php echo $this->conn_details['password']; ?>" />
<input type="hidden" name="db_name" value="<?php echo $this->conn_details['db_name']; ?>" />
<table class="table">
	<thead>
		<tr>
			<td>Select</td>
			<td>Table name</td>
			<td>Num Columns</td>
		</tr>
	</thead>
	<tbody>
<?php foreach($this->tables as $table) { ?>
		<tr>
			<td><input name="tables" type="checkbox" value="<?php echo $table['table_name']; ?>" /></td>
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
<?php } ?>