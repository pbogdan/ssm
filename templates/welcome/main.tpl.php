<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-bottom: 10px; border: 1px dotted #aaa">
	<tr>
		<td>
			<br />
			<table width="90%" cellspacing="1" cellpadding="0" border="0" align="center">
				<tr>
					<td>
					&middot;<b><?= i18n('Warning') ?></b><br /><br />
					<?= i18n('It\'s highly recommended to create backup of your databases before working on them with this tool.') ?>
					<br /><br />
					</td>
				</tr>
			</table>
			<table width="90%" cellspacing="1" cellpadding="0" border="0" align="center">
				<tr>
					<th><?= i18n('Name') ?></th>
					<th><?= i18n('Path') ?></th>
				</tr>
				<?php foreach($databases as $database) { ?>
				<tr bgcolor="#eeeeee">
					<td><a href="index.php/database/main?db=<?= $database['name'] ?>"><?= $database['name'] ?></a></td>
					<td><?= $database['path'] ?></td>
				</tr>
				<?php } ?>
			</table>
			<br />
		</td>
	</tr>
</table>
