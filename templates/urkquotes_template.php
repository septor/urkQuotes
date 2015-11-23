<?php
/*
 * urkQuotes - An IRC quote displaying plugin for e107
 *
 * Copyright (C) 2015 Patrick Weaver (http://trickmod.com/)
 * For additional information refer to the README.md file.
 *
 */
$URKQUOTES_TEMPLATE['start'] = '';
$URKQUOTES_TEMPLATE['quote'] = '
<div class="container-fluid">
	<div class="row">
		<div class="center col-xs-6 col-md-2">
			#{ID} {REPORT}
			<br />
			{RATING}
		</div>
		<div class="col-xs-12 col-md-10">
			{QUOTE}
		</div>
	</div>
</div>
';
$URKQUOTES_TEMPLATE['end'] = '';
?>
