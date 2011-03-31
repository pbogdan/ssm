<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-bottom: 10px; border: 1px dotted #aaa">
	<tr align="center">
		<td width="20%"><b>Database <?= $_GET['db'] ?>:</b></td>
		<td width="27%" onmouseover="this.style.background='#c4d3da'" onmouseout="this.style.background='#fff'"><a href="index.php/database/main?db=<?= $_GET['db'] ?>"><?= i18n('Tables') ?></a></td>
		<td width="27%" onmouseover="this.style.background='#c4d3da'" onmouseout="this.style.background='#fff'"><a href="index.php/database/query?db=<?= $_GET['db'] ?>"><?= i18n('Query') ?></a></td>
		<td width="27%" onmouseover="this.style.background='#c4d3da'" onmouseout="this.style.background='#fff'"><a href="index.php/export/main?db=<?= $_GET['db'] ?>"><?= i18n('Export') ?></a></td>
	</tr>
</table>