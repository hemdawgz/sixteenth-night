<?php

//All dummy values for now
/*$GMTOffset= -4;
$adjustedTime = time() + (3600 * $GMTOffset);
$busyTime = 0;
$deadlnTime = 1367798400;
$timeLeft = ($deadlnTime - $adjustedTime - $busyTime);*/

require 'lib.php';

$userID = 'superman';

$userData = load_data($userID);

$timeLeft = $userData->timeRemaining();

$deadlnTime = $userData->deadlineTime;

date_default_timezone_set($userData->timeZone);

//These are the things that actually get printed out (temporary,
//I plan on letting the browser handle that JS later on for live updates)

$deadlnTimeStr = date("g:i a", $deadlnTime);

$deadlnDateStr = date("M d, Y", $deadlnTime);

if ($timeLeft < 3600) {
	$timeLeftStr = floor($timeLeft/60) . " minutes";
}
else {
	$timeLeftStr = floor($timeLeft/3600) . " hours";
}


$pagecontents = <<<EOPAGE
<!DOCTYPE html>
<head>
	<link type="text/css" rel="stylesheet" href="stylesheet.css" />
	<link href="http://fonts.googleapis.com/css?family=Arvo:400,700" rel='stylesheet' type='text/css' />
	<title>Sixteenth Night</title>
</head>
<body>
	<div id="maintext">
		<div id="header">
			You have
		</div>
		<div id="time_left"><strong>
			${timeLeftStr}
		</strong></div>
		<div id="static">
			of free time remaining
		</div>
		<div class="deadln">
			before <strong>${deadlnTimeStr}</strong>,
		</div>
		<div class="deadln"><strong>
			${deadlnDateStr}
		</strong></div>
	</div>
	<div id="overlay"></div>
	<div id="slideout">
		<div id="pull_tab">
			<img src="settings.png" />
		</div>
		<div id="slideout_inner">
			<iframe src="settings.php" width="100%" seamless>Looks like iframes aren't supported by your browser. You should really consider upgrading.</iframe>
		</div>
	</div>
</body>
EOPAGE;
echo $pagecontents;

?>