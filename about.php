<?php define('IN_APP', true);
/** 
 * YU Free Food
 * about.php - Site about page
 *
 * @author Yudi Rosen <yudi42@gmail.com>
 * @package YU Free Food Website
 */
require_once('./global.inc.php');

$Template->ptitle = 'About';
$Template->activelink = 'about';
?>
<div id="fp_top">
	<h1>About The Site</h1>
</div>

<p>
	Problem: College students are perpetually hungry.<br/>
	Problem #2: College students are perpetually poor.<br />
	Solution: College events tend to offer free food!<br />
	Problem: "there was free pizza last night??"<br />
	Solution: This site.
</p>
<p>
	<h3>What is this??</h3>
	yFood was created by a student at Yeshiva University who had a bigger stomach than wallet. He noticed the vast amounts of e-mails being sent out daily for various events 
	advertising free food, and decided to share this with everyone. He then proceeded to skip 15 classes, 8 meals, and a free event to make this website.
</p>

<p>
	He also would like to take this opportunity to remind you that the food is for the events, please at least pretend to be interested in whatever the subject may be. Don't 
	eat n' run - they'll eventually catch on and just stop the food.
</p>

<p>
	<i>Oh and since people keep asking - no, the name 'yFood' doesn't have anything to do with Yeshiva. The 'Y' happens to be my first initial.</i>
</p>

<h3>Credits:</h3>
<ul>
	<li>Site developed by Yudi Rosen</li>
</ul>

<b>Thanks to:</b>
<ul>
	<li>Josh P. - for the Templating engine + being generally awesome</li>
	<li>Egor O. - for the MySQL DBAC object + being generally Canadian</li>
	<li>Pocket - for the food icons</li>
</ul>

<b>This site uses:</b>
<ul>
	<li><a href="http://jquery.com/">jQuery Javascript library</li>
	<li><a href="http://jqueryui.com/">jQuery UI library</li>
	<li><a href="http://web-kreation.com/tutorials/nice-clean-sliding-login-panel-built-with-jquery/">jQuery sliding panel</a></li>
	<li><a href="http://www.visualpharm.com/animals_icon_set/">VisualPharm Animal Icons</a></li>
</ul>

<p>
	<i><b>For the techies:</b> This site is coded in PHP5, uses a MySQL database, and is hosted on an Apache2 + Debian box.</i>
</p>