<?php

require 'lib.php';

/* $savedata = array("userID" => "superman",
				"numOfActivities" => 3,
				//Format for "activities" array: each activity is associated
				//with an ordered array containing its length and the time of
				//day it begins in seconds.
				"activities" => array(array("Eating", 7200, 39600),
									array("Sleeping", 28800, 82800),
									array("Commuting", 7200, 28800)),
				"deadlineTime" => 1369627200,
				"GMTOffset" => -4);
 */
$activityExample = array(array("Eating", 7200, 39600),
					array("Sleeping", 28800, 82800),
					array("Commuting", 7200, 28800));

$save1 = new userData('superman', 3, $activityExample, 1369627200, -4, 'America/Toronto');
$dir  = "userdata/";
file_put_contents($dir.$save1->userID, serialize($save1));

//echo "I tried. Maybe it don't work. Who knows.";

$loadedData = load_data('superman');

$activityTableHTML = "";

for ($i = 0; $i < $loadedData->numOfActivities	; $i++) {
	$activityTableHTML = $activityTableHTML . 
	"<tr><td>{$loadedData->activities[$i][0]}</td>
	<td>{$loadedData->activities[$i][1]}</td>
	<td>{$loadedData->activities[$i][2]}</td></tr>";
}

$ohshit = $loadedData->timeRemaining();

$pageContents = <<<EOPAGE

<!DOCTYPE html>
<html>
	<head>
		<title>
		Saving and loading user data in PHP
		</title>
	</head>
	<body>
		<h1>
			{$loadedData->userID}'s Data
		</h1>
		<table style="text-align: center;">
			<strong>
				<tr>
					<td>Activity Name</td>
					<td>Activity duration</td>
					<td>Activity time of day</td>
				</tr>
			</strong>
			${activityTableHTML}
		</table>
		<h1>${ohshit}</h1>
	</body>
</html>
EOPAGE;

echo $pageContents;
//echo $loadedData['activities'][0][0];
?>