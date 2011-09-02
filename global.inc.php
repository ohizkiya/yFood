<?php if(!defined('IN_APP')) die();
/** 
 * yFood
 * global.inc.php - Global init script
 *
 * @author Yudi Rosen <yudi42@gmail.com>
 * @package yFood application
 */

// 1) Load up our configuration & start the session
session_start();

require_once('./config.inc.php');

// 2) Set up our environment:
error_reporting($config['error_reporting']);

ini_set("include_path", $config['include_folder']);

function __autoload($classname) {
	require_once(preg_replace('/[^\w]/', '', $classname) . '.class.php');
}

// 3) Connect to the database, and get the DB auth data out of the way...
try {
	$DB = new mysql($config['db_hostname'], $config['db_username'], $config['db_password'], $config['db_database']);
	unset($config['db_hostname'], $config['db_username'], $config['db_password'], $config['db_database']);
}
catch(Exception $e) {
	die('<b>Database error:</b> ' . $e->getMessage());
}

// 4) Load up our additional objects:
$User     = new user();
$Event    = new event();
$csrf     = csrf::getCSRF();
$Template = new template();
$config['csrf']  = $csrf->getKey();
?>