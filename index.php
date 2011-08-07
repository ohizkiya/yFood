<?php define('IN_APP', true);
/**
 * YU Free Food
 * index.php - Site home page
 *
 * @author Yudi Rosen <yudi42@gmail.com>
 * @package yFood application
 */

require_once('./global.inc.php');

$Template->ptitle = 'Home';
$Template->activelink = 'home';

// Get our list of campuses:
$DB->query("SELECT * FROM {$config['db_prefix']}campuses AS c WHERE c.id IN (SELECT campus_id FROM {$config['db_prefix']}events WHERE campus_id=c.id)");
$campuses = $DB->fetch_row_set();

// Get our list of food types:
$DB->QUERY("SELECT * FROM {$config['db_prefix']}foodtypes AS f WHERE f.id IN (SELECT fid FROM {$config['db_prefix']}foodserved WHERE fid=f.id)");
$foodtypes = $DB->fetch_row_set();
?>

<script type="text/javascript">$(function() {$("#startdate").datepicker({ dateFormat: 'DD, M d, yy' });$("#enddate").datepicker({ dateFormat: 'DD, M d, yy' });});</script>

<script type="text/javascript">
	function save_event(eid) {
		$.ajax({url: '<?php echo $config['site_url'] ?>ajax.php',type:'POST',data:{action:'save',eid:eid,key:'<?php echo $config['csrf']; ?>'},
			success: function(html){
				$("#fp_top").append(html);
			}
		});
	}

	function email_event(eid) {
		$.ajax({url: '<?php echo $config['site_url'] ?>ajax.php',type:'POST',data:{action:'email',eid:eid,key:'<?php echo $config['csrf']; ?>'},
			success: function(html){
				$("#fp_top").append(html);
			}
		});
	}
</script>

<div id="fp_top">
	<form action="#" method="post" onsubmit="return(($('#startdate').val()=='Start Date'||$('#startdate').val()==''||$('#enddate').val()=='End Date'||$('#enddate').val()=='')?false:true);">
		<fieldset style="border:none;">
			<input class="textbox" type="text" id="startdate" name="startdate" value="<?php echo $_POST['startdate'] ? htmlentities($_POST['startdate'], ENT_QUOTES) : 'Start Date'; ?>" onfocus="this.value=(this.value=='Start Date')?'':this.value;" onblur="this.value=(this.value=='')?'Start Date':this.value;var holder=this.value;this.value='';this.value=holder;" /> 
			<input class="textbox" type="text" id="enddate" name="enddate" value="<?php echo $_POST['enddate'] ? htmlentities($_POST['enddate'], ENT_QUOTES) : 'Start Date'; ?>" onfocus="this.value=(this.value=='End Date')?'':this.value;" onblur="this.value=(this.value=='')?'End Date':this.value;var holder=this.value;this.value='';this.value=holder;"/> 
		
			<select name="campus" class="select"> 
				<option value="0" selected="selected">Any Campus</option>

				<?php
				foreach($campuses as $campus) {
					echo "\t\t\t\t<option value=\"{$campus['id']}\">{$campus['name']}</option>\n";
				}
				?>
			</select>

			<select name="foodtype" class="select"> 
				<option value="0" selected="selected">Any Food</option>
				<?php
				foreach($foodtypes as $foodtype) {
					echo "\t\t\t\t<option value=\"{$foodtype['id']}\">{$foodtype['name']}</option>\n";
				}
				?>
			</select>

			<input class="button" type="submit" name="search" value="Search!" />
		</fieldset>
	</form>
</div>

<?php
if(!empty($_POST['search'])) {

	$from = strtotime($_POST['startdate']);
	$to   = strtotime($_POST['enddate']);

	if(($from > $to) && ($from && $to)) {
		$search_error = "End Date can't be before Start Date!";
	} else {
		$search_error = $from ? false : 'Invalid Start Date!';
		$search_error = $to   ? false : 'Invalid End Date!';
	}
}

// Get our events from the DB:
$events = ($from && $to && !$search_error) ? $Event->search_events($from, $to, $_POST['campus'], $_POST['foodtype']) : $Event->get_events();

if(!events) {
	$search_error = $Event->Error;
}

if($search_error) {
	echo '<div class="errormsg">' . $search_error . '</div>';
}

if(count($events) == 0) {
	echo '<div class="errormsg">No events found matching your query!</div>';
}
else {

echo '<div id="eventlist">';
		foreach($events as $event) {

			// Break up our data (particularly the date):
			$month = date('M', $event['time']);
			$day   = date('j', $event['time']);
			$time  = date('g:i', $event['time']);
			$date  = date('l, F j Y, \a\t g:ia', $event['time']);
			$notes = empty($event['notes']) ? '' : "<p>{$event['notes']}</p>";
			$foods = !empty($event['food']) ? implode('<br />', $event['food']) : '<i>Unknown</i>';

			if($User->is_loggedin()) {
				$icons = <<<HTML
				<ul class="ui_icons" class="ui-widget ui-helper-clearfix">
					<li class="ui-state-default ui-corner-all" onclick="save_event('{$event['id']}');" title="Save to My Events"><span class="ui-icon ui-icon-folder-open"></span></li>
					<li class="ui-state-default ui-corner-all" onclick="email_event('{$event['id']}');" title="Share this event"><span class="ui-icon ui-icon-mail-closed"></span></li>
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

HTML;
		}
	?>
</div>
<?php } ?>