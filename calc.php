<?php

error_reporting(0);

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    http_response_code(400);
    exit;
}

$inputJSON = file_get_contents('php://input');

if ($inputJSON === false)
{
    http_response_code(400);
    exit;
}

$input = json_decode($inputJSON, TRUE);

if ($input === null)
{
    http_response_code(400);
    exit;
}

// Validation start
if (!array_key_exists("startDate", $input)
    || !array_key_exists("sum", $input)
    || !array_key_exists("term", $input)
    || !array_key_exists("percent", $input)
    || !array_key_exists("sumAdd", $input)) {
        http_response_code(400);
        exit;
}

function isDateFormatValid($date){
    $result = explode(".", $date);
    if(sizeof($result) !== 3){
        return false;
    }
    return checkdate($result[1], $result[0], $result[2]);
}

$date_start = DateTime::createFromFormat("d.m.Y", $input["startDate"]);
if ($date_start === false || !isDateFormatValid($input["startDate"]))
{
    http_response_code(400);
    exit;
}
$date_start->setTime(0, 0);

$sum = $input["sum"];
if (!is_int($sum) ||  $sum < 1000 || $sum > 3000000)
{
    http_response_code(400);
    exit;
}

$term = $input["term"];
if (!is_int($term) ||  $term < 1 || $term > 60)
{
    http_response_code(400);
    exit;
}

$percent = $input["percent"];
if (!is_int($percent) ||  $percent < 3 || $percent > 100)
{
    http_response_code(400);
    exit;
}
$percent = $percent / 100;

$sumAdd = $input["sumAdd"];
if (!is_int($sumAdd) ||  $sumAdd < 0 || $sumAdd > 3000000)
{
    http_response_code(400);
    exit;
}
// Validation end

function is_leap_year($year)
{
    return ((($year % 4) == 0) && ((($year % 100) != 0) || (($year %400) == 0)));
}

$sumN = $sum;

$currentDay = $date_start->format('j');
$currentMonth = $date_start->format('n');
$currentYear = $date_start->format('Y');

$daysInThisYear = is_leap_year($currentYear) ? 366 : 365;

$prevPayDate = $date_start;

for ($i=0; $i < $term; $i++)
{ 
    $currentMonth = $currentMonth + 1;
    if($currentMonth > 12){
        $currentMonth = 1;
        $currentYear = $currentYear + 1;
        $daysInThisYear = is_leap_year($currentYear) ? 366 : 365;
    }
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

    $payDayCurrentMonth = min($daysInMonth, $currentDay);

    $payDate = (new DateTime())->setDate($currentYear, $currentMonth, $payDayCurrentMonth)->setTime(0, 0);

    $intervalDays = $payDate->diff($prevPayDate)->format("%a");

    $sumN = $sumN + ($sumN + $sumAdd) * $intervalDays * ($percent / $daysInThisYear);

    $prevPayDate = $payDate;
}

$sumN = round($sumN + $sumAdd * $term, 2, PHP_ROUND_HALF_DOWN);

header('Content-Type: application/json; charset=utf-8');

echo json_encode(array('sum' => $sumN));
