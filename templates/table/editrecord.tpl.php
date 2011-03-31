<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-bottom: 5px; border: 1px dotted #aaa">
	<tr>
		<td>
			<br />
			<table width="90%" cellspacing="1" cellpadding="0" border="0" align="center">
				<tr>
					<th><?= i18n('Name') ?></td>
					<th><?= i18n('Type') ?></td>
					<th width="5%"><?= i18n('NULL') ?></td>
					<th><?= i18n('Value') ?></td>
				</tr>
				<form name="columns" method="POST">
				<?php foreach($cols as $col) { ?>
				<tr bgcolor="#eeeeee" valign="top">
					<td><?php if($col['primary']) { ?><u><?php } ?><?= $col['name'] ?><?php if($col['primary']) { ?></u><?php } ?></td>
					<td><?= $col['type'] ?></td>
					<td>
					<input type="checkbox" name="<?= $col['name'] ?>[null]" value="1"
					<?php if($col['null'] && $col['value'] == 'NULL') { ?>
					checked
					<?php } ?>
					<?php if($col['primary'] || !$col['null']) { ?> disabled<?php } ?>
					
					onClick="if(this.checked) document.forms['columns'].elements['<?= $col['name'] ?>[value]'].value=''" 
					
					/>
					</td>
					<td>
					<?php if($col['type'] == 'VARCHAR' || preg_match('#TEXT#i', $col['type'])) { ?>
					<textarea name= "<?= $col['name'] ?>[value]" rows="4" cols="30" <?php if($col['primary']) { ?>disabled<?php } ?>
					onChange="if(this.value == '')  setCheckBox('columns', '<?= $col['name'] ?>[null]', true); else setCheckBox('columns', '<?= $col['name'] ?>[null]', false);"
					><?php if($col['value'] != 'NULL') { ?><?= $col['value'] ?><?php } ?></textarea>
					<?php } else { ?>
					<input type="text" name= "<?= $col['name'] ?>[value]" style="height: 20px" value="<?php if($col['value'] != 'NULL') { ?><?= $col['value'] ?><?php } ?>" <?php if($col['primary']) { ?>disabled<?php } ?>
					 onChange="if(this.value == '')  setCheckBox('columns', '<?= $col['name'] ?>[null]', true); else setCheckBox('columns', '<?= $col['name'] ?>[null]', false);"
					 />
					<?php } ?>
					</td>
				</tr>
				<?php } ?>
				<input type="hidden" name="db" value="<?= $_GET['db'] ?>" />
				<input type="hidden" name="tbl" value="<?= $_GET['tbl'] ?>" />
				<input type="hidden" name="action" value="updateRecord" />
				<?php foreach($cols as $col) { ?>
				<input type="hidden" name="old<?= $col['name'] ?>" value="<?= $col['value'] ?>" />
				<?php } ?>
			</table>
			<table width="90%" cellspacing="0" cellpadding="0" border="1" align="center">
				<tr>
					<td><input type="submit" value="<?= i18n('Submit') ?>" style="width: 100%; height: 20px" /></td>
				</tr>
			</table>
			</form>
			<br />
		</td>
	</tr>
</table>