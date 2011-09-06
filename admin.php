<?php define('IN_APP', true);
/** 
 * yFood
 * admin.php - Site administration
 *
 * @author Yudi Rosen <yudi42@gmail.com>
 * @package yFood application
 */
require_once('./global.inc.php');

// We need to be an administrator:
if(!$User->is_loggedin() || $_SESSION['mgroup'] != $config['mgroup']['admin']) {
	header("Location: {$config['site_url']}");
	die();
}

$Template->ptitle = 'Administration';
?>
TODO: Administrator Page