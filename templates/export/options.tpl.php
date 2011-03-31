<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-bottom: 5px; border: 1px dotted #aaa">
	<tr>
		<td>
			<form action="index.php/export/view" method="GET" name="export">
			<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-bottom: 1px">
				<tr>
					<td valign="top" width="48%">
						<fieldset>
						<legend><?= i18n('Select table') ?></legend>
						<br />
						<select name="tables[]" multiple="true" style="width: 90%">
							<?php foreach($tables as $table) { ?>
							<option value="<?= $table ?>" <?php if($table == $selected) { ?>selected<?php }?> /><?= $table ?></option>
							<?php } ?>
						</select>
						<a href="" onclick="setSelectOptions('export', 'tables[]', true); return false"><?= i18n('Select All') ?></a> /
						<a href="" onclick="setSelectOptions('export', 'tables[]', false); return false"><?= i18n('UnSelect All') ?></a>
						</fieldset>
					</td>
					<td width="4%"></td>
					<td valign="top" width="48%">
						<fieldset>
							<legend><?= i18n('Select Format') ?></legend>
							<fieldset>
								<legend><input type="radio" name="format" value="sql" />SQL</legend>
								<br />
								<input type="checkbox" name="sql[structure]" value="true" />
								<label for="sql[structure]"> <?= i18n('Structure') ?></label><br />
								<input type="checkbox" name="sql[data]" value="true" />
								<label for="sql[data]"> <?= i18n('Data') ?></label><br />
							</fieldset>
							<fieldset>
								<legend><input type="radio" name="format" value="xml" />XML</legend>
								<br />
								<input type="checkbox" name="xml[structure]" value="true" />
								<label for="xml[structure]"> <?= i18n('Structure') ?></label><br />
								<input type="checkbox" name="xml[data]" value="true" />
								<label for="xml[data]"> <?= i18n('Data') ?></label>
							</fieldset>
							<fieldset>
								<legend><input type="radio" name="format" value="csv" />CSV</legend>
								No Options
								<input type="hidden" name="csv[data]" value="true" />
							</fieldset>
						</fieldset>
					</td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" value="<?= i18n('Submit') ?>" /></td>
				</tr>
			</table>
			<input type="hidden" name="db" value="<?= $_GET['db'] ?>" />
			</form>
		</td>
	</tr>
</table>