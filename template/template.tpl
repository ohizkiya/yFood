<!DOCTYPE html>
<html>
 
<head> 
	<title><!--PAGE_TITLE--></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="<!--TEMPLATE_WEB_PATH-->css/stylesheet.css" />
	<link rel="stylesheet" type="text/css" href="<!--TEMPLATE_WEB_PATH-->css/jquery.css" />
	<link rel="stylesheet" type="text/css" href="<!--TEMPLATE_WEB_PATH-->css/slide.css" />

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js"></script>
	<!-- <script type="text/javascript" src="<!--TEMPLATE_WEB_PATH-->js/jquery-ui-1.8.7.custom.min.js"></script> -->

	<script type="text/javascript">
		$(document).ready(function() {
			$("#open").click(function(){
			$("div#panel").slideDown("slow");});
			$("#close").click(function(){$("div#panel").slideUp("slow");});
			$("#toggle a").click(function (){$("#toggle a").toggle();});

			$('ul.ui_icons li').hover(function() { $(this).addClass('ui-state-hover'); },function() { $(this).removeClass('ui-state-hover');});
		});
	</script>
</head>
 
<body>
	<div id="page">

		<div id="header">

			<div id="privacy"></div>

			<div id="toppanel">
				<?php
				if($User->is_loggedin()) {
					$username = htmlentities(ucwords($User->is_loggedin()), ENT_QUOTES);

					echo <<<HTML

				<div class="tab">
					<ul class="login">
						<li class="left">&nbsp;</li>
						<li>Hello, {$username}!</li>
						<li class="sep">|</li>
						<li><a href="./user?action=settings">Settings</a></li>
						<li class="sep">|</li>
						<li><a href="./user?action=logout">Log Out</a></li>
						<li class="right">&nbsp;</li>
					</ul>
				</div>
HTML;
				}
				else {

				echo <<<HTML

				<div id="panel">
					<div class="section">
						<h1>Welcome to yFood!</h1>
						<h2>Please log in or sign up.</h2>
						<p>You'll need an account on this site in order to use the My Events system and to submit events.</p>
						<p>Registration is free and simple, and only takes a minute.</p>
					</div>
			
					<div class="section">
						<form action="./user?action=register" method="post">
							<h1 class="h1_register">Not a member yet? Sign Up!</h1>
							<label for="reg_email">E-mail:</label>
							<input class="textbox" type="text" name="reg_email" id="reg_email" value="" />
					
							<label for="reg_password">Password:</label>
							<input class="textbox" type="password" name="reg_password" id="reg_password" />
					
							<label><a href="./privacy" onclick="$('#privacy').load('./privacy.php?plain=true').dialog({title:'Privacy Policy',position:'top',width:350,modal:true});return false;">We don't spam.</a></label>

							<input type="hidden" name="key" value="{$config['csrf']}" />
					
							<input type="submit" name="submit" value="Register" class="bt_register" />
						</form>
					</div>
			
					<div class="section right">
						<form action="./user?action=login" method="post">
							<h1 class="h1_login">Member Login</h1>
							<label for="login_email">E-mail:</label>
							<input class="textbox" type="text" name="login_email" id="login_email" value="" />
					
							<label for="login_password">Password:</label>
							<input class="textbox" type="password" name="login_password" id="login_password" />
					
							<label><a href="./user?action=lostpassword">Lost your password?</a></label>

							<input type="hidden" name="key" value="{$config['csrf']}" />
					
							<input type="submit" name="submit" value="Login" class="bt_login" />
						</form>
					</div>
				</div>

				<div class="tab">
					<ul class="login">
						<li class="left">&nbsp;</li>
						<li>Hello, Stranger!</li>
						<li class="sep">|</li>
						<li id="toggle">
							<a id="open" class="open" href="#">Log In | Register</a>
							<a id="close" style="display: none;" class="close" href="#">Close Panel</a>
						</li>
						<li class="right">&nbsp;</li>
					</ul>
				</div>
HTML;
			}
?>

			</div>

			<div class="left">
				<div id="header_logo"></div>
			</div>

			<div class="right">
				<div id="header_login"></div>
				<div id="header_navigation">
					<!--PAGE_LINKS-->
				</div>
			</div>
		</div>

		<?php
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) { ?>
		<div class="critical">
			Your browser smells like Internet Explorer. Please upgrade to a supported browser, such as <a href="http://firefox.com">Mozilla Firefox</a> or <a href="http://www.google.com/chrome/">Google Chrome</a>.
		</div>

		<?php } ?>

		<div id="content">
			<!--PAGE_CONTENT-->
		</div>

		<div id="footer">
			Powered by <a href="http://github.com/yrosen/yFood">yFood</a><?php echo ($_SESSION['mgroup'] == $config['mgroup']['admin']) ? '  | <a href="./admin">Administration</a>' : '.'; ?>

		</div>
	</div>
</body>

</html>
