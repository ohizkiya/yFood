<?php define('IN_APP', true);
/**
 * yFood
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

switch($_POST['action']) {
	case 'save':
		echo $Event->save_event($_POST['eid']) ? 'success' : 'error';
		break;

	case 'unsave':
		echo $Event->unsave_event($_POST['eid']) ? 'success' : 'error';
		break;

	case 'email':
		echo 'Email!';
		break;

	case 'delete':
		if($_SESSION['mgroup'] == $config['mgroup']['admin']) {
			$Event->delete_event($_POST['eid']);
		}

		break;

	default:
		header("Location: {$config['site_url']}");
		die();
}
?>