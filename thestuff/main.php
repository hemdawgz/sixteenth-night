<?php

//All dummy values for now
$busyTime = 0;
$deadlnTime = 1369688400;
$timeLeft = ($deadlnTime - time() - $busyTime);

//These are the things that actually get printed out
$deadlnTimeStr = "before " . date("g:i a", $deadlnTime) . ",";

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
	<title>Sixteenth Night</title>
</head>
<body>
	<p class="static">
		You have
	</p>
	<p id="time_left">
		${timeLeftStr}
	</p>
	<p class="static">
		of free time remaining
	</p>
	<p id="deadln_time">
		${deadlnTimeStr}
	</p>
	<p id="deadln_date">
		${deadlnDateStr}
	</p>
</body>
EOPAGE;
echo $pagecontents;

?>