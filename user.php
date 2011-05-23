<?php define('IN_APP', true);
/** 
 * YU Free Food
 * user.php - User authentication & account management
 *
 * Fun Fact: This page is a mess.
 *
 * @author Yudi Rosen <yudi42@gmail.com>
 * @package yFood application
 */
require_once('./global.inc.php');

// Valid pages to redirect to:
// TODO: THIS.
$returns = array('my', 'submit', 'user');

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
			$errormsg = $User->Error ? '<div class="errormsg">'.htmlentities($User->Error, ENT_QUOTES).'</div>' : '';
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

	case 'settings':
	default:
		if(!$User->is_loggedin()) {
			$title = 'Member Login';
			$errormsg = '<div class="errormsg">You must be logged in to access this page!</div>';
			$require_login = true;
			break;
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
 
		<input name="submit" type="submit" class="button" value="Login" /> 
	</form>
</div>
HTML;
}
elseif($require_reset == true) {
	// TODO: Automated e-mailing
	// mail('yudi42@gmail.com', 'Test', 'This e-mail is a test!', "From: yfood@thequipster.org\r\nReply-To: yfood@thequipster.org\r\nX-Mailer: yFood\r\n");

	echo <<<HTML
	<p style="text-align:center;">
		If you need to reset your password, send an email to yfood@thequipster.org from the email account you used to register on yFood with.<br />
		A new password will be emailed to you.
	</p>
HTML;
}
elseif($require_settings == true) {
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
			Account Overview<br />
			E-mail address<br />
			# of reservations<br />
			Delete Account
		</div>
 		<div id="settings-pass">
			Change Password
		</div>
		<div id="settings-del">
			<b>Delete Account</b><br />
 
			<form action="./user?action=deleteaccount" method="post">
				<b style="color:red;">Warning:</b> This will instantly and permamently delete your account and reservations.<br /><br />

				<input type="checkbox" id="confirm_delete" name="confirm_delete" value="" />
				<label for="confirm_delete">I understand that clicking the button below will delete my account and all associated data.</label><br /><br />

				<input type="hidden" name="key" value="{$config['csrf']}" /> 
 
				<input name="submit" type="submit" class="button" value="Delete" /> 
			</form>
		</div>
	</div>
HTML;
}
?>