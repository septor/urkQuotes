<?php
/*
 * urkQuotes - An IRC quote displaying plugin for e107
 *
 * Copyright (C) 2015 Patrick Weaver (http://trickmod.com/)
 * For additional information refer to the README.md file.
 *
 */
require_once('../../class2.php');
if (!getperms('P'))
{
	header('location:'.e_BASE.'index.php');
	exit;
}
e107::lan('urkquotes', 'admin', true);

class urkquotes_adminArea extends e_admin_dispatcher
{
	protected $modes = array(

		'main'	=> array(
			'controller' 	=> 'quotes_ui',
			'path' 			=> null,
			'ui' 			=> 'quotes_form_ui',
			'uipath' 		=> null
		),
		'starplayers' => array(
			'controller'	=> 'starplayers_ui',
			'path'			=> null,
			'ui'			=> 'starplayers_form_ui',
			'uipath'		=> null
		),
	);

	protected $adminMenu = array(
		'main/list'				=> array('caption'=> 'Manage Quotes', 'perm' => 'P'),
		'main/create'			=> array('caption'=> 'Create New Quote', 'perm' => 'P'),
		'starplayers/list'		=> array('caption' => 'Manage Star Players', 'perm' => 'P'),
		'starplayers/create'	=> array('caption' => 'Create New Star Player', 'perm' => 'P'),
		'main/prefs'			=> array('caption'=> LAN_PREFS, 'perm' => 'P'),
	);

	protected $adminMenuAliases = array(
		'main/edit'	=> 'main/list',
		'startplayers/edit' => 'starplayers/list',
	);

	protected $menuTitle = 'urkQuotes';
}

class quotes_ui extends e_admin_ui
{
	protected $pluginTitle		= 'urkQuotes';
	protected $pluginName		= 'urkquotes';
	protected $table			= 'quotes';
	protected $pid				= 'id';
	protected $perPage			= 20;
	protected $batchDelete		= true;
	protected $listOrder		= 'id DESC';

	protected $fields = array(
		'checkboxes' =>  array(
			'title' => '',
			'type' => null,
			'data' => null,
			'width' => '5%',
			'thclass' => 'center',
			'forced' => '1',
			'class' => 'center',
			'toggle' => 'e-multiselect',
		),
		'id' =>  array (
			'title' => LAN_ID,
			'data' => 'int',
			'width' => '5%',
			'inline' => true,
			'help' => '',
			'readParms' => '',
			'writeParms' => '',
			'class' => 'left',
			'thclass' => 'left',
		),
		'rating' =>   array (
			'title' => 'Rating',
			'type' => 'text',
			'data' => 'str',
			'width' => 'auto',
			'inline' => true,
			'help' => '',
			'readParms' => '',
			'writeParms' => '',
			'class' => 'left',
			'thclass' => 'left',
		),
		'quote' => array (
			'title' => 'Quote',
			'type' => 'textarea',
			'data' => 'str',
			'width' => 'auto',
			'help' => '',
			'readParms' => '',
			'writeParms' => '',
			'class' => 'left',
			'thclass' => 'left',
		),
		'datestamp' => array (
			'title' => LAN_DATESTAMP,
			'type' => 'datestamp',
			'data' => 'str',
			'width' => 'auto',
			'inline' => true,
			'filter' => true,
			'help' => '',
			'readParms' => '',
			'writeParms' => '',
			'class' => 'left',
			'thclass' => 'left',
		),
		'status' => array (
			'title' => 'Status',
			'type' => 'dropdown',
			'data' => 'str',
			'width' => 'auto',
			'inline' => true,
			'help' => '',
			'readParms' => '',
			'writeParms' => '',
			'class' => 'left',
			'thclass' => 'left',
		),
		'options' => array (
			'title' => LAN_OPTIONS,
			'type' => null,
			'data' => null,
			'width' => '10%',
			'thclass' => 'center last',
			'class' => 'center last',
			'forced' => '1',
		),
	);

	protected $fieldpref = array('id', 'rating', 'datestamp', 'status');

	//	protected $preftabs        = array('General', 'Other' );
	protected $prefs = array(
		'popularThreshold' => array(
			'title' => 'Popular Threshold',
			'tab' => 0,
			'type' => 'text',
			'data' => 'str',
			'help' => 'Select the amount of positive votes a quote must get before it becomes popular.'
		),
		'unpopularThreshold' => array(
			'title' => 'Unpopular Threshold',
			'tab' => 0,
			'type' => 'text',
			'data' => 'str',
			'help' => 'Select the amount of negative votes a quote must get before it becomes unpopular.'
		),
		'submitClass' => array(
			'title' => 'Submit Quote Userclass',
			'tab' => 0,
			'type' => 'userclass',
			'data' => 'str',
			'help' => 'Select a userclass that is able to submit quotes for approval.'
		),
		'approveClass' => array(
			'title' => 'Quote Approval Userclass',
			'tab' => 0,
			'type' => 'userclass',
			'data' => 'str',
			'help' => 'Select a userclass that can approved pending quotes.'
		),
		'autoapproveClass' => array(
			'title' => 'Auto Quote Approval Userclass',
			'tab' => 0,
			'type' => 'userclass',
			'data' => 'str',
			'help' => 'Select a userclass that automatically has their submitted quote approved.'
		),
		'colorizeLines' => array(
			'title' => 'Colorize the lines of quotes based on who says them?',
			'tab' => 0,
			'type' => 'boolean',
			'data' => 'str',
			'help' => 'Query the database and colorize quote lines if they match a user\'s name.'
		),

	);

	public function init()
	{
		$this->status = array(
			'pending' => 'Pending',
			'approved' => 'Approved',
			'denied' => 'Denied'
		);
		$this->fields['status']['writeParms'] = $this->status;
	}

	public function beforeCreate($new_data)
	{
		$new_data['rating'] = '0';
		$new_data['quote'] = htmlspecialchars(addslashes($new_data['quote']));
		return $new_data;
	}

	public function afterCreate($new_data, $old_data, $id)
	{
	}

	public function onCreateError($new_data, $old_data)
	{
	}

	public function beforeUpdate($new_data, $old_data, $id)
	{
		if($old_data['quote'] == "")
		{
			$new_data['quote'] = htmlspecialchars(addslashes($old_data['quote']));
		}
		else
		{
			$new_data['quote'] = $old_data['quote'];
		}
		return $new_data;
	}

	public function afterUpdate($new_data, $old_data, $id)
	{
	}

	public function onUpdateError($new_data, $old_data, $id)
	{
	}
}

class starplayers_ui extends e_admin_ui
{
	protected $pluginTitle		= 'urkQuotes';
	protected $pluginName		= 'urkquotes';
	protected $table			= 'starPlayers';
	protected $pid				= 'id';
	protected $perPage			= 20;
	protected $batchDelete		= true;
	protected $listOrder		= 'id DESC';

	protected $fields = array(
		'checkboxes' =>  array(
			'title' => '',
			'type' => null,
			'data' => null,
			'width' => '5%',
			'thclass' => 'center',
			'forced' => '1',
			'class' => 'center',
			'toggle' => 'e-multiselect',
		),
		'id' =>  array (
			'title' => LAN_ID,
			'data' => 'int',
			'width' => '5%',
			'inline' => true,
			'help' => '',
			'readParms' => '',
			'writeParms' => '',
			'class' => 'left',
			'thclass' => 'left',
		),
		'usernames' =>   array (
			'title' => 'Usernames',
			'type' => 'text',
			'data' => 'str',
			'width' => 'auto',
			'inline' => true,
			'help' => 'Split each username with a comma (,). NO SPACES.',
			'readParms' => '',
			'writeParms' => '',
			'class' => 'left',
			'thclass' => 'left',
		),
		'hexcolor' => array (
			'title' => 'HTML Hex Color',
			'type' => 'text',
			'data' => 'str',
			'width' => 'auto',
			'help' => 'Generally something along the lines of #000000.',
			'readParms' => '',
			'writeParms' => '',
			'class' => 'left',
			'thclass' => 'left',
		),
		'options' => array (
			'title' => LAN_OPTIONS,
			'type' => null,
			'data' => null,
			'width' => '10%',
			'thclass' => 'center last',
			'class' => 'center last',
			'forced' => '1',
		),
	);

	protected $fieldpref = array('usernames', 'hexcolor');

	//	protected $preftabs        = array('General', 'Other' );
	protected $prefs = '';

	public function init()
	{
	}

	public function beforeCreate($new_data)
	{
	}

	public function afterCreate($new_data, $old_data, $id)
	{
	}

	public function onCreateError($new_data, $old_data)
	{
	}

	public function beforeUpdate($new_data, $old_data, $id)
	{
	}

	public function afterUpdate($new_data, $old_data, $id)
	{
	}

	public function onUpdateError($new_data, $old_data, $id)
	{
	}
}

class quotes_form_ui extends e_admin_form_ui
{
}
class starplayers_form_ui extends e_admin_form_ui
{
}

new urkquotes_adminArea();

require_once(e_ADMIN."auth.php");
e107::getAdminUI()->runPage();

require_once(e_ADMIN."footer.php");
exit;
?>
