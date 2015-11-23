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
$mes = e107::getMessage();
$pref = e107::pref('urkquotes');

if(check_class($pref['approveClass']))
{
	if(isset($_GET['approve']))
	{
		$update = array(
			'status' => 'approved',
			'WHERE' => 'id = '.intval($_GET['approve']),
		);

		if($sql->update('quotes', $update))
		{
			$mes->addSuccess('Quote #'.$_GET['approve'].' approved');
			echo $mes->render();
		}
		else
		{
			$mes->addError('Quote not update: '.mysql_error());
			echo $mes->render();
		}
	}
	if(isset($_GET['deny']))
	{
		$update = array(
			'status' => 'denied',
			'WHERE' => 'id = '.intval($_GET['deny']),
		);

		if($sql->update('quotes', $update))
		{
			$mes->addSuccess('Quote #'.$_GET['deny'].' denied.');
			echo $mes->render();
		}
		else
		{
			$mes->addError('Quote not update: '.mysql_error());
			echo $mes->render();
		}
	}

	if($sql->count('quotes', '(status)', 'WHERE status IN("pending", "reported")') > 0)
	{
		$text = '
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th style="width:5%;">ID</th>
					<th>Quote</th>
					<th style="15%;">Status</th>
					<th style="width:10%;">Options</th>
				</tr>
			</thead>
			<tbody>';
		$quotes = $sql->retrieve('quotes', '*', 'status IN("pending", "reported")', true);
		foreach($quotes as $quote)
		{
			$status = '<span class="label label-'.($quote['status'] == 'pending' ? 'info-">Pending' : 'warning">Reported').'</span>';
			$text .= '
				<tr>
					<td class="center">'.$quote['id'].'</td>
					<td>'.$tp->toHtml($quote['quote']).'</td>
					<td>'.$status.'</td>
					<td class="center">
						<a href="?approve='.$quote['id'].'"><span class="label label-success"><span class="glyphicon glyphicon-ok"></span></span></a>
						<a href="?deny='.$quote['id'].'"><span class="label label-danger"><span class="glyphicon glyphicon-remove"></span></span></a>
					</td>
				</tr>';
		}
		$text .= '
			</tbody>
		</table>';
	}
	else
	{
		$text = '<div class="center">Good news! There\'s no quotes in the queue!</div>';
	}
}
else
{
	$text = "You do not have access to process quotes.";
}

e107::getRender()->tablerender('Quote Queue', $text);
require_once(FOOTERF);
?>
