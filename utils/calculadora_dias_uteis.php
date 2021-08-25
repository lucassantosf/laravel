<?php

echo getWorkingDaysCount('2019-11-01','2019-11-30');

function getWorkingDaysCount($from, $to) {
    $workingDays = [1, 2, 3, 4, 5];        //Working days (week days)
    $holidayDays = ['*-12-25', '*-01-01','*-11-15']; //Holidays array, add desired dates to this array 

    $from = new DateTime($from);
    $to = new DateTime($to);
    $to->modify('+1 day');
    $interval = new DateInterval('P1D');
    $periods = new DatePeriod($from, $interval, $to);

    $days = 0;
    foreach ($periods as $period) {
      if (!in_array($period->format('N'), $workingDays)) continue;
      if (in_array($period->format('Y-m-d'), $holidayDays)) continue;
      if (in_array($period->format('*-m-d'), $holidayDays)) continue;
      $days++;
  }
  return $days;
}