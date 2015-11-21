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
}
?>
