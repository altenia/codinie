<html>
<head>
	<title><?php echo '[' . APP_NAME . ']' . $this->title; ?></title>
	<link rel="stylesheet" href="<?php echo $this->context_path; ?>/public/bootstrap/css/bootstrap.css">
	<script src="<?php echo $this->context_path; ?>/public/require-jquery.js" data-main="<?php echo $this->require_js_main; ?>"></script>
</head>
<body>
	<div class="container">
		<div class="page-header">
			<h1><?php echo APP_NAME; ?>
			  <small><?php echo APP_TAGLINE; ?></small>
			</h1>
		</div>
		<div class="row">
			<div class="span3">
				<div class="well sidebar-nav">
        <ul>
<li><a href="<?php echo route_url($this->request_context, 'Home', 'index'); ?>">HOME</a></li>
<li><a href="<?php echo route_url($this->request_context, 'Project_Main', 'index'); ?>">PROJECTS</a></li>
<li><a href="<?php echo route_url($this->request_context, 'Admin_Main', 'index'); ?>">ADMIN</a></li>
		</ul>
				</div>
			</div>
			<div class="span9">
<?php if (!empty($this->breadcrumb)) { ?>
<ul class="breadcrumb">
<?php foreach ($this->breadcrumb as $section) { 
	if (!empty($section[0])) {?>
	<li><a href="<?php echo $section[0]; ?>"><?php echo $section[1]; ?></a> <span class="divider">/</span></li>
<?php } else { ?>
	<li class="active"><?php echo $section[1]; ?></li>
<?php }
} ?>
</ul>
<?php } ?>

<?php echo $this->content; ?>
			</div>
		</div>
		<footer>
        <p><?php echo APP_NAME . ' (version '. APP_VERSION . ')'; ?> | <a href="<?php echo route_url($this->request_context, 'Content', 'show', array( 'page'=>'about.html')); ?>">About<a></p>
		</footer>
	</div>
</body>
</html>