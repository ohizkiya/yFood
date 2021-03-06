<?php define('IN_APP', true);
/** 
 * yFood
 * event.php - View Event page
 *
 * @author Yudi Rosen <yudi42@gmail.com>
 * @package yFood application
 */
require_once('./global.inc.php');

$Template->ptitle = 'View Event';

$id = intval($_GET['id']);

if(intval($id) < 1) {
	header("Location: {$config['site_url']}");
	die();
}
?>
<div id="fp_top">
	<h1>View Event</h1>
</div>

<script type="text/javascript">
	function save_event(eid) {
		$.ajax({url: '<?php echo $config['site_url'] ?>ajax.php',type:'POST',data:{action:'save',eid:eid,key:'<?php echo $config['csrf']; ?>'},
			success: function(html){
				if(html == 'success') {
					alert("Successfully saved event #" + eid + " to My Events");
				}
				else {
					alert("Error while saving event #" + eid + " to My Events");
				}
			}
		});
	}
</script>

<?php
if(!$event = $Event->get_event($id)) {
	print $Event->Error;
}
if(count($event) == 0) {
	echo '<p style="text-align:center;"><i>No event found matching your query!</i></p>';
}
else {
	echo '<div id="eventlist">';

	// Break up our data (particularly the date):
	$month = date('M', $event['time']);
	$day   = date('j', $event['time']);
	$time  = date('g:i', $event['time']);
	$date  = date('l, F m Y, \a\t g:ia', $event['time']);
	$notes = empty($event['notes']) ? '' : "<p>{$event['notes']}</p>";
	$foods = !empty($event['food']) ? implode('<br />', $event['food']) : '<i>Unknown</i>';

	if($User->is_loggedin()) {
		$icons = <<<HTML
		<ul class="ui_icons" class="ui-widget ui-helper-clearfix">
			<li class="ui-state-default ui-corner-all" onclick="save_event('{$event['id']}');" title="Save to My Events"><span class="ui-icon ui-icon-folder-open"></span></li>
			<li class="ui-state-default ui-corner-all" onclick="alert('Shared Event #{$event['id']}');" title="Share this event"><span class="ui-icon ui-icon-mail-closed"></span></li>
		</ul>
HTML;
	} else {
		$icons = '';
	}

	echo <<<HTML

	<div class="event" id="event_{$event['id']}">
		<div class="event_info">
			<div class="event_meta">
				<small>{$month}</small><br />
				<span style="font-size:20px;font-weight:bold;">{$day}</span>
				<div class="meta_sub">{$time}</div>
				<div class="meta_sub campus_{$event['campus_id']}">{$event['campus']}</div>
			</div>

			<div class="event_data" style="margin-left: 32px;">
				<div style="width:550px;float:left;">
					<a class="event_title" href="./e{$event['id']}">{$event['title']}</a><br />
					<small><i>{$event['host']}</i></small>
					<p>{$event['location']}<br/>
					{$date}</p>

					{$notes}
				</div>

				<div style="min-height:75px;width:200px;float:left;padding:0 5px;">
					<b style="font-size:14px;color:#21799E;">Food served:</b><br />
					<div style="padding-left:5px">{$foods}</div>
				</div>

				<div style="width:30px;float:right;">
					{$icons}
				</div>

				<div class="clear"></div>
			</div>
		</div>
	</div>
</div>
HTML;
	}

?>