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
$ren = e107::getRender();
$tp = e107::getParser();

$quotes = $sql->retrieve('quotes', '*', 'status="approved"', true);

$qc = 0;
foreach($quotes as $quote)
{
	// We need to pull in the quoteblock, parse the htmlspecialchars and explode each line into an array.
	$quoteblock = explode('<br />', $tp->toHtml(str_replace('&#092;', '', $quote['quote'])));

	$body = '<h3>'.$quote['rating'].'</h3>';
	foreach($quoteblock as $line)
	{
		// NOTE:
		// colorizeLine() does not properly work.
		// Will fix after other things are taken care of.
		$endquote .= colorizeLine($line).'<br />';
	}

	$body .= $endquote;

	$ren->tablerender($quote['id'], $body);
	$qc++;
}

if($qc == 0)
{
	$ren->tablerender('Oops!', "There's no quotes to display!");
}

require_once(FOOTERF);
?>
