<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-bottom: 5px; border: 1px dotted #aaa">
	<tr>
		<td>
			<br />
			<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center">
				<tr>
					<td colspan="6">&middot; <b><?= i18n('Create new table') ?>: <?= $tblName ?></b><br /><br /></td>
				</tr>
			</table>
			<table width="90%" cellspacing="1" cellpadding="0" border="0" align="center">
				<tr>
					<th width="3%">#</td>
					<th><?= i18n('Name') ?></td>
					<th><?= i18n('Type') ?></td>
					<th width="5%"><?= i18n('Length') ?></td>
					<th width="5%"><?= i18n('NULL') ?></td>
					<th><?= i18n('Default') ?></td>
					<th><?= i18n('Primary Key') ?></td>
				</tr>
				<form action="index.php" method="POST">
				<?php foreach(range(1, $tblColumns) as $number) { ?>
				<tr bgcolor="#eeeeee">
					<td><?= $number ?></td>
					<td><input type="text" name="fields[<?= $number ?>][name]" /></td>
					<td>
						<select name="fields[<?= $number ?>][type]">
						<option value="VARCHAR">VARCHAR</option>
						<option value="TINYINT">TINYINT</option>
						<option value="TEXT">TEXT</option>
						<option value="DATE">DATE</option>
						<option value="SMALLINT">SMALLINT</option>
						<option value="MEDIUMINT">MEDIUMINT</option>
						<option value="INTEGER">INTEGER</option>
						<option value="BIGINT">BIGINT</option>
						<option value="FLOAT">FLOAT</option>
						<option value="DOUBLE">DOUBLE</option>
						<option value="DECIMAL">DECIMAL</option>
						<option value="DATETIME">DATETIME</option>
						<option value="TIMESTAMP">TIMESTAMP</option>
						<option value="TIME">TIME</option>
						<option value="YEAR">YEAR</option>
						<option value="CHAR">CHAR</option>
						<option value="TINYBLOB">TINYBLOB</option>
						<option value="TINYTEXT">TINYTEXT</option>
						<option value="BLOB">BLOB</option>
						<option value="MEDIUMBLOB">MEDIUMBLOB</option>
						<option value="MEDIUMTEXT">MEDIUMTEXT</option>
						<option value="LONGBLOB">LONGBLOB</option>
						<option value="LONGTEXT">LONGTEXT</option>
						</select>
					</td>
					<td><input type="text" name="fields[<?= $number ?>][length]" size="3" /></td>
					<td><input type="checkbox" name="fields[<?= $number ?>][null]" value="true" /></td>
					<td><input type="text" name="fields[<?= $number ?>][default]" /></td>
					<td><input type="radio" name="primary" value="<?= $number ?>" /></td>
				</tr>
				<?php } ?>
			</table>
			<br />
			<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center">
				<tr>
					<td><input type="submit" value="<?= i18n('Submit') ?>" /></td>
				</tr>
			</table>
			<input type="hidden" name="db" value="<?= $_GET['db'] ?>" />
			<input type="hidden" name="tblName" value="<?= $_GET['tblName'] ?>" />
			<input type="hidden" name="action" value="buildTable" />
			</form>
		</td>
	</tr>
</table>	