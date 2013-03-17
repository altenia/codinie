<div class="row-fluid">
	<div class="span6">
		<h4>Getting Started</h4>
		<ul>
			<li><a href="<?php echo route_url($this, 'Content', 'show', array('page' => 'getting_started.html')); ?>">Getting Started</a></li>
		</ul>

		<h4>Latest projects</h4>
		<?php if (isset($this->projects)) { ?>
		<ul>
			<?php foreach ($this->projects as $project) { ?>
			<li><a href="<?php echo route_url($this, 'Project', 'work_on', array('prjid' => $project->id)); ?>"><?php echo $project->name . ' (' . $project->{'last-modif'} . ')'; ?></a></li>
			<?php } ?>
		</ul>
		<?php } ?>
		<a class="btn" href="<?php echo route_url($this, "Project", "form"); ?>">Create New Project</a>
	</div>
	<div class="span6">
		<h4>Activity Log</h4>
		<ul>
			<li><a href="<?php echo route_url($this, 'Content', 'show', array('page' => 'getting_started.html')); ?>">Getting Started</a></li>
		</ul>
	</div>
</div>