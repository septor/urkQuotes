<?php
/*
 * urkQuotes - An IRC quote displaying plugin for e107
 *
 * Copyright (C) 2015 Patrick Weaver (http://trickmod.com/)
 * For additional information refer to the README.md file.
 *
 */
function colorizeLine($line)
{
	$starPlayers = e107::getDb()->retrieve('starPlayers', '*', '', true);
	$words = explode(" ", $line);

	if(!empty($starPlayers[0]))
	{
		foreach($starPlayers as $player)
		{
			$usernames = explode(',', $player['usernames']);
			$colorIt = false;
			foreach($usernames as $username)
				if(strpos($words[0], $username) !== false)
					$colorIt = true;

			if($colorIt)
			{
				$newLine = '<span style="color:'.$player['hexcolor'].';">'.$line.'</span>';
				break;
			}
			else
			{
				$newLine = $line;
			}
		}
		$output = $newLine;
	}
	else
	{
		$output = $line;
	}

	return $output;
}
?>
