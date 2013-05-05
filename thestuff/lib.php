<?php
// function adjustedTime($offset) {
	// return time() + (3600*$offset);
// }

function load_data($userID) {
	return unserialize(file_get_contents("userdata/".$userID));
}

//without Google Calendar data
class userData {
	public $userID;
	public $numOfActivities;
	//$activities is an array of arrays in the format 
	//	$activities = array(array("name1", duration, time of day),
	//						array("name2", duration, time of day),
	//						...)
	public $activities;
	public $deadlineTime;
	public $GMTOffset;
	public $timeZone;
	
	public function __construct($uID, $nOA, $a, $dT, $GMTO, $tZ) {
		$this->userID = $uID;
		$this->numOfActivities = $nOA;
		$this->activities = $a;
		$this->deadlineTime = $dT;
		$this->GMTOffset = $GMTO;
		$this->timeZone = $tZ;
	}
	
	//What a frikkin' mess! Unix timestamps and human timezones, grrrarrarhhh
	public function timeRemaining() {
		$currentTime = time();
		$currentTimeOfDay = (($currentTime + (3600 * $this->GMTOffset)) % 86400);
		$beginningOfCurrentDay = $currentTime - $currentTimeOfDay;
		$totalTimeRemaining = $this->deadlineTime - $currentTime;
		$activityTimePerDay = 0;
		for ($i = 0; $i < $this->numOfActivities; $i++) {
			$activityTimePerDay += $this->activities[$i][1];
		}
		$calendarDaysRemaining = floor(($this->deadlineTime - $beginningOfCurrentDay)/86400) - 1;
		//$dailyActivityMap is an array of arrays in the format
		//	$dailyActivityMap = array(array(begin time of day, end time of day),
		//							  array(begin time of day, end time of day),
		//							  ...)
		//with one subarray for each activity in $activities.
		$dailyActivityMap = array();
		for ($i = 0; $i < $this->numOfActivities; $i++) {
			$beginTime = $this->activities[$i][2];
			$endTime = $beginTime + $this->activities[$i][1];
			$dailyActivityMap[$i] = array($beginTime, $endTime);
		}
		//determine amount of free time between current time and midnight/24:00
		$freeTimeBeforeMidnight = 86400 - $currentTimeOfDay;
		for ($i = 0; $i < $this->numOfActivities; $i++) {
			$begin = $dailyActivityMap[$i][0];
			$end = $dailyActivityMap[$i][1];
			if ($end > $currentTimeOfDay) {
				$freeTimeBeforeMidnight -= (min(86400, $end) - max($currentTimeOfDay, $begin));
			}
		}
		//determine amount of free time between beginning of tomorrow and end of
		//final day before deadline (e.g. if current time is 21:00 May 3 and
		//deadline is 9:00 May 6, the amount of free time between 00:00 May 4 and
		//24:00 May 5.)
		$freeTimeFullDays = (86400 - $activityTimePerDay) * $calendarDaysRemaining;
		//determine amount of free time between beginning of deadline day and deadline
		$beginningOfDeadlineDay = $beginningOfCurrentDay + (($calendarDaysRemaining + 1) * 86400);
		$deadlineTimeOfDay = $this->deadlineTime - $beginningOfDeadlineDay;
		$deadlineDayFreeTime = $deadlineTimeOfDay;
		for ($i = 0; $i < $this->numOfActivities; $i++) {
			$begin = $dailyActivityMap[$i][0];
			$end = $dailyActivityMap[$i][1];
			if ($end > 86400) {
				$deadlineDayFreeTime -= (min($deadlineTimeOfDay, ($begin - 86400)));
			}
			if ($begin < $deadlineTimeOfDay) {
				$deadlineDayFreeTime -= (min($deadlineTimeOfDay, $end) - $begin);
			}
		}
		$totalFreeTime = $freeTimeBeforeMidnight + $freeTimeFullDays + $deadlineDayFreeTime;
		return $totalFreeTime;
	}
}

?>