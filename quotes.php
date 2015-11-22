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
$pref = e107::pref('urkquotes');

if(isset($_GET['sort']))
{
	$sort = strtolower($_GET['sort']);

	if($sort == "highest") $sorting = " ORDER BY rating DESC";
	else if($sort == "lowest") $sorting = " ORDER BY rating ASC";
	else if($sort == "newest") $sorting = " ORDER BY id DESC";
	else if($sort == "oldest") $sorting = " ORDER BY id ASC";
	else if($sort == "random") $sorting = " ORDER BY RAND()";
	else $sorting = " ORDER BY id DESC";
}
else
{
	$sorting = " ORDER BY id DESC";
}

$quotes = $sql->retrieve('quotes', '*', 'status="approved"'.$sorting, true);

$qc = 0;
foreach($quotes as $quote)
{
	$quoteblock = explode('<br />', $tp->toHtml(str_replace('&#092;', '', $quote['quote'])));
	//$quoteblock = explode('<br />', str_replace('&#092;', '', $quote['quote']));

	$body = '<h3>'.$quote['rating'].'</h3>';
	foreach($quoteblock as $line)
	{
		$endquote .= ($pref['colorizeLines'] == true ? colorizeLine($line) : $line).'<br />';
	}

	$body .= $endquote;

	e107::getRender()->tablerender($quote['id'], $body);
	$qc++;

	unset($endquote, $body);
}

if($qc == 0)
{
	e107::getMessage()->addInfo('Oops! There are no quotes to display!');
	echo e107::getMessage()->render();
}

require_once(FOOTERF);
?>
