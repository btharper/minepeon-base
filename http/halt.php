<?php
include_once('functions.inc.php');
include_once('settings.inc.php');
this_session_start();
login_check("quick");
?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Halting MinePeon</title>
		<link href="/css/bootstrap.min.css" rel="stylesheet" />
		<link href="/css/bootstrap-minepeon.css" rel="stylesheet" />
		<link href="/css/halt.css" rel="stylesheet" />
		<script type="text/javascript" src="/js/halt.countdown.js" async="async" defer="defer" />
	</head>
	<body>
		<div class="center-page">
			<p><h1>Shutting Down MinePeon</h1></p>
			<p>It should be safe to unplug in</p>
			<p><h1 id="countdown">30</h1></p>
			<p>seconds.</p>
		</div>
	</body>
</html><?php exec('/usr/bin/sudo /usr/bin/halt > /dev/null 2>&1 &'); ?>