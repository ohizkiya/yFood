<?php define('IN_APP', true);
/** 
 * yFood
 * user.php - User authentication & account management
 *
 * Fun Fact: This page is a mess.
 *
 * @author Yudi Rosen <yudi42@gmail.com>
 * @package yFood application
 */
require_once('./global.inc.php');

/*
 * 1) Are we logged in or not?
 * If yes, we can only access settings pages or logout
 *
 * If no, we can only access login, registration, and password reset
 */
switch(strtolower($_GET['action'])) {

	case 'login':
		if($User->is_loggedin()) {
			header("Location: {$config['site_url']}");
			die();
		}

		// All good, show our login form!
		$title = "Log In";

		if(!$User->login($_POST['login_email'], $_POST['login_password'])) {
			$errormsg = $User->Error ? '<div class="errormsg">' . htmlentities($User->Error, ENT_QUOTES) . '</div>' : '';
			$require_login = true;
		} else {
			header("Location: {$config['site_url']}");
			die();
		}

		break;
	
	case 'logout':
		$User->logout();

		header("Location: {$config['site_url']}");
		die();

	case 'register':
		if($User->is_loggedin()) {
			header("Location: {$config['site_url']}");
			die();
		}

		// All good, show our register form!
		$title = 'Register';

		if(!$User->register($_POST['reg_email'], $_POST['reg_password'])) {
			$errormsg = !empty($User->Error) ? '<div class="errormsg">'.htmlentities($User->Error, ENT_QUOTES).'</div>' : '';
			$require_register = true;
		} else {
			header("Location: {$config['site_url']}");
			die();
		}

		break;

	case 'lostpassword':
		if($User->is_loggedin()) {
			header("Location: {$config['site_url']}");
			die();
		}

		$title = 'Reset Password';
		$require_reset = true;
		
		break;

	case 'deleteaccount':
		if(!$User->is_loggedin()) {
			header("Location: {$config['site_url']}");
			die();
		}

		// Was it something I said? :(
		if($User->is_loggedin() && $csrf->checkKey($_POST['key'])) {
			$User->delete_account($_SESSION['username']);
			$User->logout();
			header("Location: {$config['site_url']}");
			die();
		}
		else {
			$errormsg = '<div class="errormsg">Could not delete account!</div>';
		}

		break;

	case 'changepass':
		if(!$User->is_loggedin()) {
			header("Location: {$config['site_url']}");
			die();
		}

		if($User->is_loggedin() && $csrf->checkKey($_POST['key'])) {
			if($_POST['newpass'] != $_POST['newpass2']) {
				$errormsg = '<div class="errormsg">Passwords do not match!</div>';
			}
			elseif(!$User->check_pass($_SESSION['username'], $_POST['currpass'])) {
				$errormsg = '<div class="errormsg">Incorrect current password!</div>';
			}
			elseif($User->change_password($_SESSION['uid'] , $_POST['newpass'])) {
				$errormsg = '<div class="notifymsg">Password has been updated.</div>';
			}
			else {
				$errormsg = $User->Error ? '<div class="errormsg">' . htmlentities($User->Error) . '</div>' : '<div class="errormsg">Could not change password!</div>';
			}
		}
		else {
			$errormsg = '<div class="errormsg">Could not change password!</div>';
		}

		$require_settings = true;

		break;


	case 'settings':
	default:
		if(!$User->is_loggedin()) {
			header("Location: {$config['site_url']}");
			die();
		}

		$title = 'Account Settings';
		$require_settings = true;
		break;
}
	
$Template->ptitle = $title;
?>
<div id="fp_top">
	<h1><?php echo $title; ?></h1>
</div>

<?php
if($require_login == true) {
	$email = $_POST['login_email'] ? htmlentities($_POST['login_email'], ENT_QUOTES) : '';

echo <<<HTML

<div id="userpage">
	{$errormsg}
 
	<form action="./user?action=login" method="post"> 
		<label for="email">E-mail:</label><br />
		<input class="textbox logintextbox" type="text" name="login_email" id="email" value="{$email}" /><br /><br />
					
		<label for="password">Password:</label> <br />
		<input class="textbox logintextbox" type="password" name="login_password" id="password" /><br /><br />

		<input type="hidden" name="key" value="{$config['csrf']}" /> 
 
		<input name="submit" type="submit" class="button" value="Login" /><br /><br />

		<small>No account? <a href="./user?action=register">sign up</a>!</small><br />
		<small><a href="./user?action=lostpassword">Forgot your password?</a></small>
	</form>
</div>
HTML;
}
elseif($require_register == true) {
	$email = $_POST['reg_email'] ? htmlentities($_POST['reg_email'], ENT_QUOTES) : '';

echo <<<HTML

<div id="userpage">
	{$errormsg}
 
	<form action="./user?action=register" method="post">
		<label for="email">E-mail:</label><br />
		<input class="textbox logintextbox" type="text" name="reg_email" id="email" value="{$email}" /><br /><br />
					
		<label for="password">Password:</label> <br />
		<input class="textbox logintextbox" type="password" name="reg_password" id="password" /><br />

		<input type="hidden" name="key" value="{$config['csrf']}" /> 
 
		<input name="submit" type="submit" class="button" value="Register" /> 
	</form>
</div>
HTML;
}
elseif($require_reset == true) {
	echo <<<HTML
	<p style="text-align:center;">
		If you need to reset your password, send an email to yfood@thequipster.org from the email account you used to register on yFood with.<br />
		A new password will be emailed to you.
	</p>
HTML;
}
elseif($require_settings == true) {
	// Get user data:
	$username    = htmlentities($_SESSION['username']);
	$membersince = htmlentities($_SESSION['joindate']); // TODO- formatting

	// Get reservations count:
	$s_uid       = intval($_SESSION['uid']);
	$DB->query("SELECT COUNT(*) FROM {$config['db_prefix']}reservations WHERE uid='{$s_uid}'");
	$results     = $DB->fetch_row();
	$num_reservs = intval($results['COUNT(*)']);
	
	echo <<<HTML

	<script type="text/javascript">
		\$(function(){\$('#tabs').tabs();});
	</script>

	<div id="tabs" style="margin: 5px 0 5px 0;">
		<ul>
			<li><a href="#settings-acct">Account</a></li>
			<li><a href="#settings-pass">Password</a></li>
			<li><a href="#settings-del">Delete Account</a></li>
		</ul>

		<div id="settings-acct">
			<p>
				<h2>Account Overview</h2>
				<b>Username:</b> {$username}<br />
				<b>Member since:</b> {$membersince}<br />
				<b>Number of reservations:</b> {$num_reservs}
			</p><br />

			<h2>SMS Settings:</h2>
			<form method="post" action="">
				<input type="checkbox" name="enable_sms" value="" /> Enable SMS notifications prior to my saved events.<br />
				Text me <input type="text" value="10" class="textbox" name="sms_priormins" style="width:30px;" /> minutes prior to event start.<br />
				Phone number: <input type="text" class="textbox" value="" name="sms_phonenumber" /> Carrier: 
				<select class="select">
					<!-- TODO: Get these values out of here, perform strict checking, etc -->
					<option value="cingularme.com">Cingular</option>
					<option value="messaging.nextel.com">Nextel</option>
					<option value="messaging.sprintpcs.com">Sprint</option>
					<option value="tmomail.net">T-Mobile</option>
					<option value="vtext.com">Verizon</option>
					<option value="vmobl.com">Virgin Mobile</option>
				</select><br /><br />
				<input type="submit" value="Submit" />
			</form>
		</div>
 		<div id="settings-pass">
			<h2>Change Password</h2>

			{$errormsg}

			<form action="./user?action=changepass#settings-pass" method="post">
				<strong>Current Password:</string><br />
				<input type="text" class="textbox" value="" name="currpass" /><br /><br /><br />

				<strong>New Password:</strong><br />
				<input type="text" class="textbox" value="" name="newpass" /><br /><br />

				<strong>Confirm New Password:</strong><br />
				<input type="text" class="textbox" value="" name="newpass2" />

				<input type="hidden" name="key" value="{$config['csrf']}" />

				<br /><br />
 
				<input name="submit" type="submit" class="button" value="Change Password" /> 
			</form>
		</div>
		<div id="settings-del">
			<h2>Delete Account</h2><br />
 
			<form onsubmit="if($('#confirm_delete').attr('checked') == false) { alert('Please confirm you want to delete your account!'); return false; } return true;" action="./user?action=deleteaccount" method="post">
				<div class="errormsg">Warning:</b> This will instantly and permamently delete your account and all My Events reservations.</div><br />

				<input type="checkbox" id="confirm_delete" name="confirm_delete" value="" />
				<label for="confirm_delete">I understand that clicking the button below will delete my account and all associated data.</label><br /><br />

				<input type="hidden" name="key" value="{$config['csrf']}" /> 
 
				<input name="submit" type="submit" class="button" value="Delete Account" /> 
			</form>
		</div>
	</div>
HTML;
}
?>