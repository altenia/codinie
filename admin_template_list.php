<?php 
require_once 'config.php';
require_once FRAMEWORK_PATH . 'LayoutController.php';
require_once CLASSES_PATH .  'TemplateManager.php';
require_once CLASSES_PATH .  'utils.php';

$context = new stdClass;
$context->contextPath = '';
$context->pattern = '';
$context->template_content = 'aa';
$context->template_name = 'bbb';
$context->templates = TemplateManager::instance()->get_list(null);
?>

<h3>Templates <small><a href="<?php echo route_url($context, 'Admin_Main', 'template_form'); ?>">Create new</a></small></h3>

<form name="ListTemplates" action="<?php echo route_url($context, 'Admin_Main', 'template_list'); ?>" method="get">
<input type="text" name="pattern" value="<?php echo $context->pattern; ?>" /> <input name="submit" type="submit" value="Filter" />
</form>
<?php if(!empty($context->templates)) { ?>
<table class="table">
	<thead>
		<tr>
			<td>Template</td>
			<td>Description</td>
			<td>Unit</td>
		</tr>
	</thead>
	<tbody>
<?php foreach($context->templates as $template) { ?>
		<tr>
			<td><a href="<?php echo route_url($context, 'Admin_Main', 'template_form', array('id' => $template['id'])); ?>"><?php echo ifndef('name', $template, $template['id']); ?></a></td>
			<td><?php echo $template['description']; ?></td>
			<td><?php echo $template['unit']; ?></td>
		</tr>
<?php } ?>
    <tr>
	</tbody>
</table>
</form>
<?php } ?>


<?php if(!empty($context->template_content)) { ?>
<form name="FormTemplate" action="<?php echo route_url($context, 'Admin_Main', 'template_form'); ?>" method="post">
	<input type="hidden" name="template_name" value="<?php echo $context->template_name; ?>" /> 
	<textarea name="" class="input-xxlarge" rows="20" >
<?php echo $context->template_content; ?>
	</textarea>
	<input name="submit" type="submit" value="Filter" />
</form>
<?php } ?>