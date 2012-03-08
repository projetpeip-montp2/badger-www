<?php

session_start();
define('IN_ADMIN', true);

require_once('includes/config.inc.php');
require_once('includes/functions.inc.php');
require_once('includes/pages.inc.php');
if (!isAdmin())
	die(getLangString('ERR_NOT_ADMIN'));
require_once('views/top.php');

try
{
	$db = new PDO('mysql:host='.$config['db_server'].';dbname='.$config['db_name'].';', $config['db_username'], $config['db_password']);
}
catch (PDOException $e)
{
	die(getLangString('ERR_DB_CONNECT'));
}
if (isset($_GET['page']) && !empty($_GET['page']) && in_array($_GET['page'], $valid_pages) === TRUE)
{
	require_once('models/'.$_GET['page'].'.php');
}
else
{
	require_once('models/index.php');
}
require_once('views/bottom.php');

?>
