<h4>Projects</h4>
<?php if (isset($this->projects)) { ?>
<table class="table table-striped">
	<thead>
		<tr>
			<td></td>
			<td>name</td>
			<td>owner</td>
			<td>language</td>
			<td>created</td>
			<td></td>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($this->projects as $project) { ?>
		<tr>
			<td><a href="<?php echo route_url($this, 'Project_Main', 'form', array('prjid' => $project->id)); ?>">edit</a></td>
			<td><a href="<?php echo route_url($this, 'Project_Main', 'work_on', array('prjid' => $project->id)); ?>"><?php echo $project->name ?></a></td>
			<td><?php echo $project->owner ?></td>
			<td><?php echo $project->language ?></td>
			<td><?php echo $project->created ?></td>
			<td><a href="">[X]</a></td>
	<?php } ?>
	</tbody>
</table>
<?php } ?>
<a class="btn" href="<?php echo route_url($this, "Project_Main", "form"); ?>">Create New Project</a>