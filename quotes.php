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
$sc = e107::getScBatch('urkquotes', true);
$template = e107::getTemplate('urkquotes');
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

if(isset($_GET['report']))
{
	$update = array(
		'status' => 'reported',
		'reported' => 1,
		'WHERE' => 'id = '.intval($_GET['report']),
	);
	$sql->update('quotes', $update);
	e107::getMessage()->addInfo('You have reported quote #'.$_GET['report'].'. It will be reviewed shortly.');
	echo e107::getMessage()->render();
}

$quotes = $sql->retrieve('quotes', '*', 'status="approved"'.$sorting, true);

if($quotes)
{
	$text = $tp->parseTemplate($template['start'], false, $sc);
	foreach($quotes as $quote)
	{
		$block = explode('<br />', $tp->toHtml($quote['quote']));

		$endquote = '';
		foreach($block as $line)
		{
			$endquote .= ($pref['colorizeLines'] == true ? colorizeLine($line) : $line).'<br />';
		}

		$sc->setVars(array(
			'id' => $quote['id'],
			'rating' => $quote['rating'],
			'reported' => array($quote['reported'], $quote['id']),
			'quote' => $endquote,
		));
		$text .= $tp->parseTemplate($template['quote'], false, $sc);
		unset($endquote, $block);
	}
	$text .= $tp->parseTemplate($template['end'], false, $sc);
}
else
{
	$text = '<div class="center">Oops! There are no quotes to display!</div>';
}

e107::getRender()->tablerender('Quotes', $text);

require_once(FOOTERF);
?>
