<?php

if(!defined('IN_ADMIN'))
{
	die();
}

require_once('lang/'.$config['lang'].'.php');

function isAdmin()
{
	// TODO: A modifier dès qu'on saura comment différencier administrateur et élève
	return (1);
}

function getLangString($id)
{
	global $lang;
	global $config;
	
	return ($lang[$config['lang']][$id]);
}

?>