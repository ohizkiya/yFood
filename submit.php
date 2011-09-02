<?php define('IN_APP', true);
/** 
 * yFood
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

<script type="text/javascript">$(function() {$("#event_date").datepicker({ dateFormat: 'DD, M d, yy' });});</script>

<div id="fp_top">
	<h1>Submit Event</h1>
</div>

<form method="post" action="">
	<div class="notifymsg">Please include as much information as possible about the event!</div>

	<div style="padding:8px;">
		<strong>Event Name:</strong><br />
		<input class="textbox" type="text" id="event_name" name="event_name" value=""  style="width:500px;" /><br /><br />

		<div class="left" style="width:300px;">
		<strong>Event Date:</strong><br />
			<input class="textbox" type="text" id="event_date" name="event_date" value=""  /><br /><br />
		</div>

		<div class="left">
		<strong>Event Time:</strong><br />
			<input class="textbox" type="text" id="event_time" name="event_time" value=""  /><br /><br />
		</div>

		<div class="clear"></div>

		<strong>Event Location:</strong><br />
		<input class="textbox" type="text" id="event_location" name="event_location" value=""  /><br /><br />

		<strong>Event information:</strong><br />
		<i><b>Example:</b> event host, types of food served, description, contact information.</i>
		<textarea name="quote" class="textarea" rows="8" cols="80" style="width:500px;"></textarea><br /><br />

		<input type="hidden" name="k" value="<?php echo $csrf->getKey(); ?>" />
		<input class="button" type="submit" name="search" value="Submit!" />
	</div>
</form>