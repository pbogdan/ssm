<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-bottom: 5px; border: 1px dotted #aaa">
	<tr>
		<td>
			<br />
			<table width="90%" cellspacing="1" cellpadding="0" border="0" align="center">
				<tr>
					<td colspan="5">&middot; <b><?= i18n('Meaning of values in parentheses in function description') ?></b><br /><br />
					<?= i18n('- <b>B</b> - builtin <b>U</b> - user defined<br />- number of arguments function expects; -1 for not fixed') ?><br /><br />
					&middot; <b><?= i18n('Function arguments') ?></b><br /><br />
					<?= i18n('Please enter arguments in following format: \'a\', \'b\', \'c\' ... If you need to use "\'" within argument please escape it with "\'" (for example \'val\'\'ue\').<br />If no function selected please don\'t enclose value in "\'".') ?>
					<br /><br />
					</td>
				</tr>
				<tr>
					<th><?= i18n('Name') ?></td>
					<th><?= i18n('Type') ?></td>
					<th><?= i18n('Function') ?></td>
					<th width="5%"><?= i18n('NULL') ?></td>
					<th><?= i18n('Value') ?></td>
				</tr>
				<form name="columns" method="POST">
				<?php foreach($cols as $col) { ?>
				<tr bgcolor="#eeeeee">
					<td><?php if($col['primary']) { ?><u><?php } ?><?= $col['name'] ?><?php if($col['primary']) { ?></u><?php } ?></td>
					<td><?= $col['type'] ?></td>
					<td>
						<select name="<?= $col['name'] ?>[function]" <?php if($col['primary']) { ?>disabled<?php } ?>>
							<option value="" >-- Functions</option>
							<?php foreach($functions as $function) { ?>
							<option value="<?= $function['name'] ?>:f:<?php if($function['builtin']) { ?>true<?php } else { ?>false<?php } ?>">
							<?= $function['name'] ?> (<?php if($function['builtin']) { ?>B<?php } else { ?>U<?php } ?>:<?= $function['args'] ?>)
							</option>
							<?php } ?>
						</select>
					</td>
					<td>
					<input type="checkbox" name="<?= $col['name'] ?>[null]" value="1"
					<?php if($col['null'] && !$col['default']) { ?>
					checked
					<?php } ?>
					<?php if($col['primary'] || !$col['null']) { ?> disabled<?php } ?>
					
					onClick="if(this.checked) { document.forms['columns'].elements['<?= $col['name'] ?>[value]'].value=''; document.forms['columns'].elements['<?= $col['name'] ?>[function]'].options[0].selected=true; }" 
					
					/>
					</td>
					<td>
					<input type="text" name= "<?= $col['name'] ?>[value]" style="height: 20px" value="<?= $col['default'] ?>" <?php if($col['primary']) { ?>disabled<?php } ?>
					 onChange="if(this.value == '')  setCheckBox('columns', '<?= $col['name'] ?>[null]', true); else setCheckBox('columns', '<?= $col['name'] ?>[null]', false);"
					 />
					</td>
				</tr>
				<?php } ?>
				<input type="hidden" name="db" value="<?= $_GET['db'] ?>" />
				<input type="hidden" name="tbl" value="<?= $_GET['tbl'] ?>" />
				<input type="hidden" name="action" value="insertRecord" />
			</table>
			<table width="90%" cellspacing="0" cellpadding="0" border="1" align="center">
				<tr>
					<td><input type="submit" value="<?= i18n('Submit') ?>" style="width: 100%; height: 20px" /></td>
				</tr>
			</table>
			<br />
			<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center">
				<tr>
					<td><input type="checkbox" value="1" name="insertAnotherRecord" /> <?= i18n('Insert another record') ?></td>
				</tr>
			</table>
			</form>
			<br />
		</td>
	</tr>
</table>