<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-bottom: 5px; border: 1px dotted #aaa">
	<tr>
		<td>
			<br />
			<table width="90%" cellspacing="1" cellpadding="0" border="0" align="center">
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
			</table>
			<br />
			<table width="90%" cellspacing="1" cellpadding="0" border="0" align="center">
				<tr>
					<td>
						<br /><?= i18n('Jump to page:') ?>
						<form method="GET" action="index.php/table/browse">
						<select name="offset" onChange="submit()">
							<?php foreach($pages as $l=>$page) { ?>
								<option value="<?= $l ?>" <?php if($l == $offset) {?>selected<?php } ?>><?= $page ?></option>
							<?php } ?>
						</select>
						<input type="hidden" name="db" value="<?= $_GET['db'] ?>" />
						<input type="hidden" name="tbl" value="<?= $_GET['tbl'] ?>" />
						<input type="hidden" name="query" value="<?= $query['pure'] ?>" />
						<input type="hidden" name="limit" value="<?= $limit ?>" />
						</form>
					</td>
				</tr>
			</table>
			<table width="90%" cellspacing="1" cellpadding="0" border="0" align="center">
				<tr>
					<?php if(@$prev) { ?>
					<td align="left">
					<a href="
					index.php/table/browse?db=<?= $_GET['db'] ?>&amp;tbl=<?= $_GET['tbl'] ?>&amp;query=<?= urlencode($query['pure']) ?>&amp;offset=<?= $prevoffset ?>&amp;limit=<?= $limit ?>
					"><?= i18n('prev') ?></a>
					</td>
					<?php } ?>
					<?php if(@$next) { ?>
					<td align="right">
					<a href="
					index.php/table/browse?db=<?= $_GET['db'] ?>&amp;tbl=<?= $_GET['tbl'] ?>&amp;query=<?= urlencode($query['pure']) ?>&amp;offset=<?= $nextoffset ?>&amp;limit=<?= $limit ?>
					"><?= i18n('next') ?></a>
					</td>
					<?php } ?>
				</tr>
			</table>
			<br />
			<table width="90%" cellspacing="1" cellpadding="0" border="0" align="center">
				<tr>
					<th width="20px"></th>
					<th width="20px"></th>
				<?php foreach($cols as $col) { ?>
					<th><a href="index.php/table/browse?db=<?= $_GET['db'] ?>&tbl=<?= $_GET['tbl'] ?>&amp;orderBy=<?= urlencode($col) ?>&dir=<?= (isset($dir)) ? $dir : 'ASC' ?>&limit=<?= $limit ?>&offset=<?= $offset ?>" style="color: #ffffff"><?= $col ?></a></th>
				<?php } ?>
				</tr>
				<?php foreach($result as $row) { ?>
				<tr bgcolor="#eeeeee">
					<td align="center">
						<a href="index.php?action=editRecord&db=<?= $_GET['db'] ?>&tbl=<?= $_GET['tbl'] ?>
						<?php foreach($row as $k=>$col) { ?>
							&<?= urlencode($k) ?>=<?php if(is_null($col)) { ?>NULL<?php } else { ?><?= urlencode($col) ?><?php } ?>
						<?php } ?>">
						<img src="templates/images/edit.png" />
						</a>
					</td>
					<td align="center">
						<a href="index.php?action=deleteRecord&db=<?= $_GET['db'] ?>&tbl=<?= $_GET['tbl'] ?>
						<?php foreach($row as $k=>$col) { ?>
							&<?= urlencode($k) ?>=<?php if(is_null($col)) { ?>NULL<?php } else { ?><?= urlencode($col) ?><?php } ?>
						<?php } ?>">
						<img src="templates/images/delete.png" />
						</a>
					</td>
					<?php foreach($row as $col) { ?>
						<td><?php if(is_null($col)) { ?><i>NULL</i><?php } else { ?><?= substr($col, 0, 30) ?><?php }; if(strlen($col) > 30) { ?>...<?php } ?></td>
					<?php } ?>
				</tr>
				<?php } ?>
			</table>
			<table width="90%" cellspacing="1" cellpadding="0" border="0" align="center">
				<tr>
					<?php if(@$prev) { ?>
					<td align="left">
					<a href="
					index.php/table/browse?db=<?= $_GET['db'] ?>&amp;tbl=<?= $_GET['tbl'] ?>&amp;query=<?= urlencode($query['pure']) ?>&amp;offset=<?= $prevoffset ?>&amp;limit=<?= $limit ?>
					"><?= i18n('prev') ?></a>
					</td>
					<?php } ?>
					<?php if(@$next) { ?>
					<td align="right">
					<a href="
					index.php/table/browse?db=<?= $_GET['db'] ?>&amp;tbl=<?= $_GET['tbl'] ?>&amp;query=<?= urlencode($query['pure']) ?>&amp;offset=<?= $nextoffset ?>&amp;limit=<?= $limit ?>
					"><?= i18n('next') ?></a>
					</td>
					<?php } ?>
				</tr>
			</table>
			<br />
		</td>
	</tr>
</table>