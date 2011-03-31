<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-bottom: 5px; border: 1px dotted #aaa">
	<tr>
		<td>
			<br />
			<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center">
			<form action="index.php" method="POST" enctype="multipart/form-data">
				<tr>
					<td>&middot;<b><?= i18n('Warning') ?></b><br /><br />
					<?= i18n('Any query that you enter (through file upload or by hand) will be	executed without any confirmation. So be carefull if query contains any DROP/DELETE statements') ?>
					<br /><br />
					</td>
				</tr>
				<tr>
					<td>
					&middot; <b><?= i18n("Load SQL file") ?></b><br /><br />
					<?= i18n("Use this form to load text file containing SQL queries - for example created with export SQL feature") ?><br /><br />
					<input type="file" name="sqlFile" /><br />
					<br /><br />
					</td>
				</tr>
				<tr>
					<td>
					&middot; <b><?= i18n('Load XML file') ?></b><br /><br />
					<?= i18n("Use this form to load text file containing XML dump - for example created with export XML feature") ?><br /><br />
					<input type="file" name="xmlFile" /><br /><br />
					<br />
					</td>
				</tr>
				<tr>
					<td>
					&middot; <b><?= i18n("Enter query by hand") ?></b><br /><br />
					<textarea name="Query" rows="7" style="width: 500px"></textarea>
					</td>
				</tr>
				<tr>
					<td>
					<input type="submit" value="<?= i18n('Submit') ?>" style="width: 500px" />
					</td>
				</tr>
			</table>
			<input type="hidden" name="action" value="buildQuery" />
			<input type="hidden" name="db" value="<?= $_GET['db'] ?>" />
			</form>
			</form>
			<br />
		</td>
	</tr>
</table>