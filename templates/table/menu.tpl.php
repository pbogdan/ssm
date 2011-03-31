<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-bottom: 10px; border: 1px dotted #aaa">
	<tr align="center">
		<td width="20%"><b>Table <?= $_GET['tbl'] ?>:</b></td>
		<td width="16%" onmouseover="this.style.background='#c4d3da'" onmouseout="this.style.background='#fff'"><a href="index.php/table/main?db=<?= $_GET['db'] ?>&tbl=<?= $_GET['tbl'] ?>"><?= i18n('Structure') ?></a></td>
		<td width="16%" onmouseover="this.style.background='#c4d3da'" onmouseout="this.style.background='#fff'"><a href="index.php/table/browse?db=<?= $_GET['db'] ?>&tbl=<?= $_GET['tbl'] ?>"><?= i18n('Browse') ?></a></td>
		<td width="16%" onmouseover="this.style.background='#c4d3da'" onmouseout="this.style.background='#fff'"><a href="index.php/table/insert?db=<?= $_GET['db'] ?>&tbl=<?= $_GET['tbl'] ?>"><?= i18n('Insert') ?></a></td>
		<td width="16%" onmouseover="this.style.background='#c4d3da'" onmouseout="this.style.background='#fff'"><a href="index.php?action=cleanTable&db=<?= $_GET['db'] ?>&tbl=<?= $_GET['tbl'] ?>" onclick="return confirmLink(this, 'DELETE FROM <?= $_GET['tbl'] ?>')"><?= i18n('Empty') ?></a></td>
		<td onmouseover="this.style.background='#c4d3da'" onmouseout="this.style.background='#fff'"><a href="index.php?action=dropTable&db=<?= $_GET['db'] ?>&tbl=<?= $_GET['tbl'] ?>" onclick="return confirmLink(this, 'DROP TABLE <?= $_GET['tbl'] ?>')"><?= i18n('Drop') ?></a></td>
	</tr>
</table>