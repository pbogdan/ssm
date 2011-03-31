<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-bottom: 5px; border: 1px dotted #aaa">
	<tr>
		<td>
			<br />
			<table width="90%" cellspacing="0" cellpadding="0" border="0" align="center">
				<tr>
					<td>
						&middot;<b>About program</b><br /><br />
						First of all, this small piece of software is dedicated to <b>Scarlett
						Johansson</b>.<br /><br />
						- bugtracker on sourceforge - <a href="http://sourceforge.net/tracker/?group_id=117410" target="_blank">click</a><br />
						- project site on sourceforge - <a href="http://sourceforge.net/projects/silsm/" target="_blank">click</a><br />
						- project home site - <a href="http://silsm.sourceforge.net/" target="_blank">click</a>
						<br /><br />
						&middot;<b>About author</b><br /><br />
						<img src="templates/images/piotrbogdan.jpg" style="border: solid 1px #000" /><br /><br />
						- born on 13.03.1984<br />
						- studying computer engineering<br />
						- about 2 years of PHP experience<br />
						- 193cm tall, 70kg of live weight, shoes size 12,5 US :D<br />
						- mail - 
						<?php
						
						global $js_encode;
						$string = 'document.write(\'<a href="mailto:silence@dotgeek.org">silence [at] dotgeek [dot] org</a>\');';
		
						for ($x=0; $x < strlen($string); $x++) {
							$js_encode .= '%' . bin2hex($string[$x]);
						}
					
						?><script type="text/javascript" language="javascript">eval(unescape('<?= $js_encode ?>'))</script>
						<br /><br />
					</td>
				</tr>
			</table>
			<br />
		</td>
	</tr>
</table>
