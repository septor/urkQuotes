<?php
/*
 * urkQuotes - An IRC quote displaying plugin for e107
 *
 * Copyright (C) 2015 Patrick Weaver (http://trickmod.com/)
 * For additional information refer to the README.md file.
 *
 */
class urkquotes_shortcodes extends e_shortcode
{
	function sc_quote($parm='')
	{
		return $this->var['quote'];
	}

	function sc_rating($parm='')
	{
		return $this->var['rating'];
	}

	function sc_id($parm='')
	{
		return $this->var['id'];
	}

	function sc_report($parm='')
	{
		$item_array = $this->var['reported'];
		if(USER && $item_array[0] == 0)
		{
			$output = '<a href="'.e_PLUGIN.'urkquotes/quotes.php?report='.$item_array[1].'"><span class="glyphicon glyphicon-exclamation-sign" title="Report Quote"></span></a>';
		}
		else
		{
			$output = '';
		}

		return $output;
	}
}
?>
