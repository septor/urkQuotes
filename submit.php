<?php
/*
 * urkQuotes - An IRC quote displaying plugin for e107
 *
 * Copyright (C) 2015 Patrick Weaver (http://trickmod.com/)
 * For additional information refer to the README.md file.
 *
 */
require_once('../../class2.php');
require_once(HEADERF);
require_once(e_PLUGIN.'urkquotes/_class.php');
$sql = e107::getDb();
$tp = e107::getParser();
$frm = e107::getForm();
$mes = e107::getMessage();
$pref = e107::pref('urkquotes');

if(check_class($pref['submitClass']))
{
	if(isset($_POST['submit']))
	{
		if($_POST['quote'] != "")
		{
			$status = (check_class($pref['autoapproveClass']) ? 'approved' : 'pending');
			$insert = array(
				'rating' => 0,
				'quote' => $tp->toDb($_POST['quote'], true, false, 'no_html'),
				'datestamp' => time(),
				'status' => $status,
			);

			if($sql->insert('quotes', $insert))
			{
				$mes->addSuccess('Quote added to the queue! It will be reviewed as soon as possible.');
			}
			else
			{
				$mes->addError('Sorry, your quote was not added. Try again?');
			}
		}
		else
		{
			$mes->addInfo('Hey, you know what a good quote has? Yes, content! Add some, buddy!');
		}
	}
	echo $mes->render();

	$textarea = $frm->textarea('quote', '', '10', '', array('noresize' => true), '');
	$button = $frm->button('submit', 'Add Quote', 'submit');
	
	$text .= $frm->open('submitQuote');

	$text .= '
	<table class="table">
		<tr>
			<td class="center">'.$textarea.'</td>
		</tr>
		<tr>
			<td>
				Please respect the following rules when submitting quotes:
				<ol>
					<li>No kittens.</li>
					<li>No horsecrab pornography.</li>
				</ol>
			</td>
		</tr>
		<tr>
			<td class="right">'.$button.'</td>
		</tr>
	</table>';

	$text .= $frm->close();
}
else
{
	$text = "Sorry, but you don't have access to submit quotes.";
}

e107::getRender()->tablerender('Submit A Quote', $text);

require_once(FOOTERF);
?>
