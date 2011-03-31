<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-bottom: 5px; border: 1px dotted #aaa">
	<tr>		
		<td>
			<br />
			<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center">
				<tr>
					<td>
						&middot; <b><?= i18n('Create new columns') ?></b><br /><br />
						<form action="index.php" method="POST">
						<select name="columnsPosition" style="width: 150px">
							<option value='start'><?= i18n('At the beggining') ?></option>
							<option value='end'><?= i18n('At the end') ?></option>
							<?php foreach($cols as $col) { ?>
							<option value="<?= $col['name'] ?>"><?= i18n('After') ?> <?= $col['name'] ?></option>
							<?php } ?>
						</select>&nbsp;<?= i18n('Position of columns') ?><br />
						<input type="text" name="numCols" style="width: 150px" />&nbsp;<?= i18n('Number of columns') ?>
						<br />
						<input type="submit" value="<?= i18n('Submit') ?>" style="width: 150px" />
						<input type="hidden" name="db" value="<?= $_GET['db'] ?>" />
						<input type="hidden" name="tbl" value="<?= $_GET['tbl'] ?>" />
						<input type="hidden" name="action" value="newColumns" />
						</form>
					</td>
				</tr>
			</table>
			<br />
			<form action="index.php" method="POST">
			<table width="90%" cellspacing="1" cellpadding="0" border="0" align="center">
				<tr>
					<th width="5%"></th>
					<th><?= i18n('Name') ?></td>
					<th><?= i18n('Type') ?></td>
					<th width="5%"><?= i18n('Length') ?></td>
					<th width="10%"><?= i18n('NULL') ?></td>
					<th><?= i18n('Default') ?></td>
				</tr>
				<?php foreach($cols as $col) { ?>
				<tr bgcolor="#eeeeee">
					<td><input type="checkbox" name="selectedCols[]" value="<?= $col['name'] ?>" /></td>
					<td><?php if($col['primary']) { ?><u><?php } ?><?= $col['name'] ?><?php if($col['primary']) { ?></u><?php } ?></td>
					<td><?= $col['type'] ?></td>
					<td><?php if($col['length']) { ?><?= $col['length'] ?><?php } ?></td>
					<td><?php if($col['null']) { ?><i>NULL</i><?php } else { ?><i>NOT NULL</i><?php } ?></td>
					<td><?= $col['default'] ?></td>
				</tr>
				<?php } ?>
			</table>
			<br />
			<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center">
				<tr>
					<td>Selected:
						<select name="action" onchange="submit()">
							<option value=""></option>
							<option value="editColumns"><?= i18n('Edit') ?></option>
							<option value="dropColumns"><?= i18n('Drop') ?></option>
						</select>
					</td>
				</tr>
			</table>
			<input type="hidden" name="db" value="<?= $_GET['db'] ?>" />
			<input type="hidden" name="tbl" value="<?= $_GET['tbl'] ?>" />
			</form>
			<br />
		</td>
	</tr>
</table>