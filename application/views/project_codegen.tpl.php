<form name="Connect" action="" method="post">
<table class="table">
	<tr>
		<td>Schema in XML</td>
		<td>
    <textarea class="input-xxlarge" rows="10">
<?php echo $this->schema_xml; ?>
    </textarea>
    </td>
	</tr>

	
<?php foreach($this->generated_code as $template_name => $code) { ?>
	<tr>
		<td><?php echo $template_name; ?></td>
		<td>
    <textarea class="input-xxlarge" rows="20">
<?php echo $code; ?>
    </textarea>
	</tr>
<?php } ?>
</table>