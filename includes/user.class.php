<?php if(!defined('IN_APP')) die();
/** 
 * YU Free Food
 * /includes/user.class.php - User management and auth class
 *
 * @author Yudi Rosen <yudi42@gmail.com>
 * @package yFood application
 */
class user {
	public $Error;

	public function is_loggedin() {
		if(isset($_SESSION['username'])) {
			// Returns the first part of the e-mail address if the user is logged in
			$username = split('@', $_SESSION['username']);
			return $username[0];
		}

		return false;
	}
	
	public function register($email, $password) {
		global $config, $DB;

		if(empty($email) || empty($password)) {
			$this->Error = 'E-mail and password are required!';
			return false;
		}

		if($this->is_valid_email($email)) {
			$s_email = $DB->escape_string($email);
		}
		else {
			$this->Error = 'Invalid e-mail address!';
			return false;
		}

		if(strlen($password) < 6) {
			$this->Error = 'Password must be at least 6 characters.';
			return false;
		}
		else {
			$s_pass = sha1($password);
		}

		$s_ip   = $_SERVER['REMOTE_ADDR'];
		
		$DB->query("SELECT id FROM {$config['db_prefix']}users WHERE email='{$s_email}'");
		if($DB->fetch_num_rows() > 0) {
			$this->Error = 'E-mail is already registered!';
			return false;
		}
		
		// All good, let's go...
		$DB->query("INSERT INTO {$config['db_prefix']}users (email, password, signup_ip) VALUES ('{$s_email}', '{$s_pass}', INET_ATON('{$s_ip}'))");

		// May as well log us in...
		$this->login($email, $password);

		return true;
	}
	
	public function login($email, $password) {
		global $config, $DB;

		if(empty($email) || empty($password)) {
			$this->Error = 'E-mail and password are required!';
			return false;
		}

		if($this->is_valid_email($email)) {
			$s_email = $DB->escape_string($email);
		}
		else {
			$this->Error = 'Invalid e-mail address!';
			return false;
		}
		
		$s_pass = sha1($password);
		$s_ip   = $_SERVER['REMOTE_ADDR'];
		
		$DB->query("SELECT * FROM {$config['db_prefix']}users WHERE email='{$s_email}' AND password='{$s_pass}'");
		
		if($DB->fetch_num_rows() == 0) {
			$this->Error = 'Invalid username or password!';
			return false;
		}
		else {
			$user = $DB->fetch_row();

			$DB->QUERY("UPDATE {$config['db_prefix']}users SET latest_ip=INET_ATON('{$s_ip}') WHERE email='{$s_email}'");

			$_SESSION['uid']      = $user['id'];
			$_SESSION['username'] = $user['email'];

			return true;
		}
	}

	public function change_password($uid, $password) {
		global $config, $DB;

		$s_uid = intval($uid);

		if(strlen($password) < 6) {
			$this->Error = 'Password must be at least 6 characters.';
			return false;
		}
		else {
			$s_pass = sha1($password);
		}

		if(!$DB->query("UPDATE {$config['db_prefix']}users SET password='{$s_pass}' WHERE id=$s_uid")) {
			$this->Error = 'An error occured!';
			return false;
		}

		return true;
	}

	public function logout() {
		$_SESSION = array();
		session_destroy();
	}

	public function sent_pwd_email($email) {
		global $config, $DB;

		$s_email = $DB->escape_string($email);

		if(!$this->is_valid_email($s_email)) {
			$this->Error = 'Invalid email address!';
			return false;
		}


	}
	private function is_valid_email($email) {
		return preg_match('/^([a-z0-9]+)([._-]([0-9a-z_-]+))*@([a-z0-9]+)([._-]([0-9a-z]+))*([.]([a-z0-9]+){2,4})$/i', $email);
	}

	// TODO: Look into FACEBOOK authentication
}

?>