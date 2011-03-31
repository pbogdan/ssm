<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-bottom: 5px; border: 1px dotted #aaa">
	<tr>
		<th>Following errors occured while processing your request</th>
	</tr>
	<?php foreach($errors as $error) { ?>
	<tr>
		<td><font color="#F00"><?php echo $error ?></font></td>
	</tr>
	<?php }	?>
</table>