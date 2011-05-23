<?php define('IN_APP', true);
/**
 * YU Free Food
 * ajax.php - AJAX server-side functions
 *
 * @author Yudi Rosen <yudi42@gmail.com>
 * @package yFood application
 */
print_r($_POST);

require_once('./global.inc.php');
$Template->disable();

/*if(!$User->is_loggedin()) {
	header("Location: {$config['site_url']}");
	die();
} */

/**
 * So basically I'm not too sure how to do CSRF checking due to the AJAX part.
 * But that shouldn't matter if we're using user auth/sessions, right?
 */

switch ($_POST['action']) {
	case 'reserve':

		if($Event->reserve($_POST['eid'])) {
			echo "POSITIVELY RESERVED";
		}
		else {
			echo $Event->Error ? $Event->Error : "ERROR DURING RESERVATION!";
		}

		break;

	case 'delreserve':
		die('Delete Reservation!');
		break;

	case 'email':
		die('E-mail To Friend!');
		break;

	default:
		die('Default!');
		break;

}

?>