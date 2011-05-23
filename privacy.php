<?php define('IN_APP', true);
/**
 * YU Free Food
 * privacy.php - Privacy Policy
 *
 * @author Yudi Rosen <yudi42@gmail.com>
 * @package yFood application
 */
require_once('./global.inc.php');
$Template->ptitle = 'Privacy Policy';

if($_GET['plain'] == 'true') {
	$Template->disable();
}
else {
	echo '<div id="fp_top"><h1>Privacy Policy</h1></div>';
}
?>
<b>Our privacy policy is simple:</b>

<ol>
	<li>We need your e-mail address for the sole purpose of managing your account. We will not spam you. We will not give this information to anyone.</li>
	<li>In fact, we won't contact you at all unless you specifically request it (e.g. password resets and event reminders).
	<li>Your password is encrypted using a one-way algorithm. We do not have access to your plaintext password, and can not decrypt what we do have.</li>
	<li>You will always have the option to instantly and permamently delete your account and all data associated with it.</li>
</ol>

<!--
<p>
	This privacy notice discloses the privacy practices for yFood. This privacy notice applies solely to information collected and stored by this web site. 
	It will notify you of the following:
	<ol>
		<li>What personally identifiable information is collected from you through the web site, how it is used and with whom it may be shared.</li>
		<li>What choices are available to you regarding the use of your data.</li>
		<li>The security procedures in place to protect the misuse of your information.</li>
		<li>How you can correct any inaccuracies in the information.</li>
	</ol>
</p>

<p>
	<b>Information Collection, Use, and Sharing</b><br />
	We are the sole owners of the information collected on this site. We only have access to/collect information that you voluntarily give us via email or other direct 
	contact from you. We will not sell or rent this information to anyone.
</p>

<p>
	We will use your information to respond to you, regarding the reason you contacted us. We will not share your information with any third party outside of our organization.
</p>

<p>
	Unless you ask us not to, we may contact you via email in the future to tell you about specials, new products or services, or changes to this privacy policy.
</p>

<p>
	<b>Registration</b><br />
	In order to use some features of this website, a user must first complete the registration form. During registration a user is required to give certain 
	information (such as a password and email address). This information is only used to provide a personalized site experience for the user.
</p>

<p>
	<b>Your Access to and Control Over Information</b><br />
	You may opt out of any future contacts from us at any time. You may also delete your account at any time, which will instantly and permamently delete any 
	and all data we have about you.
</p>

<p>
	You may also do the following at any time:
	<ol>
		<li>See what data we have about you, if any.</li>
		<li>Change/correct any data we have about you.</li>
		<li>Have us delete any data we have about you.</li>
		<li>Express any concern you have about our use of your data.</li>
	</ol>
</p>

<p>
	<b>Security</b><br />
	We take precautions to protect your information. When you submit sensitive information via the website, your information is protected both online and offline.
</p>

<p>
	To futher protect your privacy, the passwords you use to access your account are encrypted via a powerful one-way hashing function. We do not have access 
	to your unencrypted passwords.
</p>

<p>
	If you feel that we are not abiding by this privacy policy, please contact us immediately.
</p>
-->