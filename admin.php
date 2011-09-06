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

<div id="fp_top">
	<h1>Administration</h1>
</div>

<script type="text/javascript">
	$(function(){$('#tabs').tabs();});
</script>

<div id="tabs" style="margin: 5px 0 5px 0;">
	<ul>
		<li><a href="#admin-overview">Overview</a></li>
		<li><a href="#admin-events">Manage Events</a></li>
		<li><a href="#admin-users">Manage Users</a></li>
		<li><a href="#admin-settings">Site Settings</a></li>
	</ul>

	<div id="admin-overview">
		<h2>Account Overview</h2>
		Number of events, users, reservations, maybe basic usage stats
	</div>

 	<div id="admin-events">
		<h2>Manage Events</h2>
		Pending submissions, edit? add new
	</div>

	<div id="admin-users">
		<h2>Manage Users</h2>
		Ban/delete, change email/pass, manually add, edit mgroup...
	</div>

	<div id="admin-settings">
		<h2>Site Settings</h2>
		site name, date formatting, etc, also campus list
 	</div>
</div>
