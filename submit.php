<?php define('IN_APP', true);
/** 
 * YU Free Food
 * about.php - Site about page
 *
 * @author Yudi Rosen <yudi42@gmail.com>
 * @package yFood application
 */
require_once('./global.inc.php');

if(!$User->is_loggedin()) {
	header("Location: {$config['site_url']}user");
	die();
}

$Template->ptitle = 'Submit Event';
$Template->activelink = 'Submit Event';
?>
<div id="fp_top">
	<h1>Submit Event</h1>
</div>

<form method="post" action="">
	<div class="notifymsg">Please include as much information as possible about the event!</div>

	Event Name:<br />
	<input class="textbox" type="text" id="event_name" name="event_name" value=""  /><br /><br />

	Event Date:<br />
	<input class="textbox" type="text" id="event_date" name="event_date" value=""  /><br /><br />

	Event Location:<br />
	<input class="textbox" type="text" id="event_location" name="event_location" value=""  /><br /><br />

	Event information:<br />
	<i><b>Example:</b> event host, types of food served, description, contact information.</i>
	<textarea name="quote" class="textarea" rows="8" cols="100"></textarea><br /><br />

	<input type="hidden" name="k" value="<?php echo $csrf->getKey(); ?>" />
	<input class="button" type="submit" name="search" value="Submit!" />
</form>