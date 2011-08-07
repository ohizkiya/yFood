<?php define('IN_APP', true);
/**
 * YU Free Food
 * ajax.php - AJAX server-side functions
 *
 * @author Yudi Rosen <yudi42@gmail.com>
 * @package yFood application
 */
require_once('./global.inc.php');
$Template->disable();

if(!$User->is_loggedin()) {
	header("Location: {$config['site_url']}");
	die();
}

/**
 * So basically I'm not too sure how to do CSRF checking due to the AJAX part.
 * But that shouldn't matter if we're using user auth/sessions, right?
 */

// For some reason this is not working as a Switch...

if($_POST['action'] == 'save') {
	// Who needs error checking?
	echo $Event->save_event($_POST['eid']) ? 'success' : 'error';
}
elseif($_POST['action'] == 'unsave') {
	echo 'UNSAVE!';
}
elseif($_POST['action'] == 'email') {
	echo 'EMAIL!';
}
else {
	echo 'ERROR!!!!';
}

?>