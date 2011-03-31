<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-bottom: 5px; border: 1px dotted #aaa">
	<tr>
		<td>
			<br />
			<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center">
				<tr>
					<td>
						&middot; <b><?= i18n('Create new table') ?></b><br /><br />
						<form action="index.php" method="POST">
						<input type="text" name="tblName" style="width: 150px" /> <?= i18n('Name of table') ?>
						<br />
						<input type="text" name="tblColumns" style="width: 150px" value="1" /> <?= i18n('Number of columns') ?><br />
						<input type="submit" value="<?= i18n('Submit') ?>" style="width: 150px" />
						<input type="hidden" name="db" value="<?= $_GET['db'] ?>" />
						<input type="hidden" name="action" value="newTable" />
						</form>
					</td>
				</tr>
			</table>
			<table width="90%" cellspacing="1" cellpadding="0" border="0" align="center">
				<tr>
					<th><?= i18n('Name') ?></th>
					<th colspan="7"><?= i18n('Actions') ?></th>
				</tr>
				<?php for($i = 0; $i < @sizeof($tables); $i++) {  $table = $tables[$i]; ?>
				<tr bgcolor="#eeeeee">
					<td><?= $table ?></td>
					<td><a href="index.php/table/main?db=<?= $_GET['db'] ?>&tbl=<?= $table ?>"><?= i18n('Structure') ?></a></td>
					<td><a href="index.php/table/browse?db=<?= $_GET['db'] ?>&tbl=<?= $table ?>"><?= i18n('Browse') ?></a></td>
					<td><a href="index.php/table/insert?db=<?= $_GET['db'] ?>&tbl=<?= $table ?>"><?= i18n('Insert') ?></a></td>
					<td><a href="index.php?action=cleanTable&db=<?= $_GET['db'] ?>&tbl=<?= $table ?>" onclick="return confirmLink(this, 'DELETE FROM <?= $table ?>')"><?= i18n('Empty') ?></a></td>
					<td><a href="index.php?action=dropTable&db=<?= $_GET['db'] ?>&tbl=<?= $table ?>" onclick="return confirmLink(this, 'DROP TABLE <?= $table ?>')"><?= i18n('Drop') ?></a></td>
				</tr>
				<?php } ?>
				<tr>
				</tr>
			</table>
			<br />
		</td>
	</tr>
</table>