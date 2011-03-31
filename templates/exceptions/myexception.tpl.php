<table border="1" width="90%" align="center" cellspacing="0" cellpadding="0">
	<tr>
		<th colspan="2" align="left">Following exception has occured: <font color="#F00"><?= $exceptionName ?></font></td>
	</tr>
	<tr>
		<td colspan="2"><br /></td>
	</tr>
	<tr>
		<td width="15%">On line:</td>
		<td><?= $exceptionLine ?></td>
	</tr>
	<tr>
		<td>In file:</td>
		<td><?= $exceptionFile ?></td>
	</tr>
	<tr>
		<td valign="top">Code trace:</td>
		<td><?= $exceptionTrace ?></td>
	</tr>
</table>
