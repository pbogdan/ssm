<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-bottom: 5px; border: 1px dotted #aaa">
	<tr>
		<td>
			<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-bottom: 1px">
				<tr>
					<td>
					<?= $header ?>
					<?php foreach($tables as $table) { ?>
					<?php if(@$table['structure']) { ?>
					<pre><?= wordwrap(htmlentities($table['structure'])) ?></pre>
					<?php } ?>
					<?php if(@$table['data']) { ?>
							<pre><?= wordwrap(htmlentities(implode("\n", $table['data']))) ?></pre>
					<?php } ?>
					<?php } ?>
					<?= $footer ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>