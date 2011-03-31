<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-bottom: 10px; border: 1px dotted #aaa">
	<tr>
		<td>
			<br />
			<table width="90%" cellspacing="1" cellpadding="0" border="0" align="center">
				<?php foreach($queries as $query) { ?><br>
				<tr>
					<th><?= i18n('Query') ?></th>
					<th><?= i18n('Time') ?></th>
					<th><?= i18n('Result') ?></th>
				</tr>
				<tr bgcolor="#eeeeee">
					<td><?= $query['text'] ?></td>
					<td><?= $query['timing'] ?></td>
					<td><?= $query['message'] ?></td>
				</tr>
				<?php if($query['isSelect']) { ?>
				<tr>
					<td colspan="3">
					<table width="100%" cellspacing="1" cellpadding="0" border="0" align="center">
						<tr>
						<?php
						$cols = $query['result'][0];
						foreach($cols as $col=>$val) { ?>
						<th><?= $col ?></th>
						<?php } ?>
						</tr>
						<?php foreach($query['result'] as $row) { ?>
						<tr bgcolor="#eeeeee">
							<?php foreach($row as $col=>$val) { ?>
							<td><?php if(is_null($val)) { ?><i>NULL</i><?php } else { ?><?= substr($val, 0, 30) ?><?php }; if(strlen($val) > 30) { ?>...<?php } ?></td>
							<?php } ?>
						</tr>
						<?php } ?>
					</table>
					</td>
				</tr>
				<?php } ?>
				<?php } ?>
			</table>
			<br />
		</td>
	</tr>
</table>