<?php if(!defined('IN_APP')) die();
/** 
 * yFood
 * /includes/event.class.php - Event object class
 *
 * @author Yudi Rosen <yudi42@gmail.com>
 * @package yFood application
 */
class event {
	public $Error;

	public function get_event($id) {
		global $config, $DB;

		$id = intval($id);

		if(!$id) {
			$this->Error = 'Invalid event ID!';
			return false;
		}

		$DB->query("SELECT e.*, c.name as campus FROM {$config['db_prefix']}events AS e JOIN {$config['db_prefix']}campuses AS c ON e.campus_id=c.id WHERE e.id='{$id}' LIMIT 1");

		$event = $DB->fetch_row();

		/* TODO: There has GOT to be a way to do this in one query...maybe an IN clause? */

		$DB->query("SELECT f.name from {$config['db_prefix']}foodtypes as f JOIN {$config['db_prefix']}foodserved AS s WHERE s.eid = '{$event['id']}' AND s.fid = f.id");

		foreach($DB->fetch_row_set() as $food) {
			$event['food'][] = $food[name];
		}
				
		return $event;
	}

	public function get_events() {
		global $config, $DB;

		$event_list = array();

		// Not only do we need the events stuff, we also need the campus name (campus.name WHERE events.campusid = campus.id):
		$where = $config['show_past_events'] ? '' : ' WHERE date >= CURDATE()';

		$DB->query("
			SELECT e.*, c.name as campus FROM {$config['db_prefix']}events AS e JOIN {$config['db_prefix']}campuses AS c 
			ON e.campus_id=c.id {$where} ORDER BY e.time DESC
		");

		$events = $DB->fetch_row_set();

		/* TODO: There has GOT to be a way to do this in one query...maybe an IN clause? */
		foreach($events as $event) {
			$foodserved = array();

			$DB->query("SELECT f.name from {$config['db_prefix']}foodtypes as f JOIN {$config['db_prefix']}foodserved AS s WHERE s.eid = '{$event['id']}' AND s.fid = f.id");

			foreach($DB->fetch_row_set() as $food) {
				$event['food'][] = $food[name];
			}

			$event_list[] = $event;
		}
				
		return $event_list;
	}

	public function add_event() {
	//public function add_event($title, $host, $date, $time, $location, $notes, $campus, $foodtypes) {
		global $config, $DB;

		// Just a test:
		$title = $DB->escape_string('Annual SYMS Shabbaton');
		$host  = $DB->escape_string('Sy Syms School of Business');
		$date  = "September 09 2011";
		$time  = "19:00";
		$location = $DB->escape_string("Beren Campus");
		$notes  = $DB->escape_string('We will be hosting the Annual SYMS Shabbaton (for SYMS and non-SYMS Students alike) next Shabbat September 9-10 in Midtown Manhattan. The weekend will have great food, events and an exciting Saturday night activity. Make sure to sign up next week for a Shabbat at a Midtown hotel. (Cost is ONLY $15 debited from your Caf-Card)');
		$campus = '2';
		$time = strtotime($date . ' ' .  $time);
		$date = strtotime($date);

		$query = "INSERT INTO yf_events(date, time, title, host, campus_id, location, notes) VALUES (FROM_UNIXTIME($date), '{$time}', '{$title}', '{$host}', '{$campus}', '{$location}', '{$notes}')";
		//$DB->query($query);
	}

	public function delete_event($id) {
		global $config, $DB;

		$id = intval($id);

		if(!$id) {
			$this->Error = 'Invalid event ID!';
			return false;
		}

		if($_SESSION['mgroup'] == $config['mgroup']['admin']) {
			// Delete event:
			$DB->query("DELETE FROM {$config['db_prefix']}events WHERE id='{$id}'");

			// Delete any food served for this event:
			$DB->query("DELETE FROM {$config['db_prefix']}foodserved WHERE eid='{$id}'");

			// Delete any reservations for this event:
			$DB->query("DELETE FROM {$config['db_prefix']}reservations WHERE eid='{$eid}'");

			return true;
		}

		$this->Error = 'Only Administrators can delete an event!';
		return false;
	}

	public function search_events($start_date, $end_date, $campus = 0, $foodtype = 0) {
		global $config, $DB;

		// Just a thought...instead of raw precise timestamp, use date() + strtotime() to get just datestamp?

		$s_start_date = intval($start_date) ? intval($start_date) : time();
		$s_end_date   = intval($end_date)   ? intval($end_date)   : time();

		$s_campus     = intval($campus)     ? intval($campus)     : 0;
		$s_foodtype   = intval($foodtype)   ? intval($foodtype)   : 0;

		// Build our search query...
		$query  = "SELECT e.*, c.name as campus FROM {$config['db_prefix']}events AS e JOIN {$config['db_prefix']}campuses AS c ON e.campus_id=c.id WHERE 1=1";
		$query .= $s_start_date ? " AND (date >= FROM_UNIXTIME({$s_start_date}))" : ' ';
		$query .= $s_end_date   ? " AND (date <= FROM_UNIXTIME({$s_end_date})) " : ' ';
		$query .= $s_campus     ? " AND e.campus_id = '{$s_campus}' " : ' ';
		$query .= $s_foodtype   ? " AND e.id IN (SELECT eid FROM {$config['db_prefix']}foodserved WHERE fid='{$s_foodtype} ')" : ' ';
		$query .= "ORDER BY e.date ASC";

		// ...and query it!
		$DB->query($query);

		$events = $DB->fetch_row_set();

		/* TODO: There has GOT to be a way to do this in one query...maybe an IN clause? */
		foreach($events as $event) {
			$foodserved = array();

			$DB->query("SELECT f.name from {$config['db_prefix']}foodtypes as f JOIN {$config['db_prefix']}foodserved AS s WHERE s.eid = '{$event['id']}' AND s.fid = f.id");

			foreach($DB->fetch_row_set() as $food) {
				$event['food'][] = $food[name];
			}

			$event_list[] = $event;
		}

		return $event_list;

		//return $DB->fetch_row_set();
	}

	public function get_my_events() {
		global $config, $DB;

		$event_list = array();

		$uid = intval($_SESSION['uid']);
		
		if(!$uid) {
			$this->Error = 'Could not determine current user.';
			return false;
		}

		// SELECT DISTINCT uid,eid FROM {$config['db_prefix']}reservations
		$DB->query("
			SELECT e.*, c.name as campus FROM {$config['db_prefix']}events AS e JOIN {$config['db_prefix']}campuses AS c 
			ON e.campus_id=c.id 
			WHERE e.id IN (SELECT eid FROM {$config['db_prefix']}reservations WHERE uid='{$uid}') 
			/* AND (date >= CURDATE()) */
			ORDER BY e.date ASC
		");

		$events = $DB->fetch_row_set();

		/* TODO: There has GOT to be a way to do this in one query...maybe an IN clause? */
		foreach($events as $event) {
			$foodserved = array();

			$DB->query("SELECT f.name from {$config['db_prefix']}foodtypes as f JOIN {$config['db_prefix']}foodserved AS s WHERE s.eid = '{$event['id']}' AND s.fid = f.id");

			foreach($DB->fetch_row_set() as $food) {
				$event['food'][] = $food[name];
			}

			$event_list[] = $event;
		}
				
		return $event_list;
	}

	public function save_event($eid) {	
		global $config, $DB;

		$s_eid = intval($eid);
		$s_uid = intval($_SESSION['uid']);

		// Duplicates are checked by the DB:
		$query = "INSERT IGNORE INTO {$config['db_prefix']}reservations (uid, eid)	VALUES ('{$s_uid}', '{$s_eid}')";
		$DB->query($query);

		// Who needs error checking?
		return true;
	}

	public function unsave_event($eid) {	
		global $config, $DB;

		$s_eid = intval($eid);
		$s_uid = intval($_SESSION['uid']);

		// Duplicates are checked by the DB:
		$DB->query("DELETE FROM {$config['db_prefix']}reservations WHERE uid='{$s_uid}' AND eid='{$s_eid}'");

		// Who needs error checking?
		return true;
	}
}
?>
