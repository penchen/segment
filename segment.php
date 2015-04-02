<?php
/**
 * Created by PhpStorm.
 * User: penchen
 * Date: 15-4-2
 * Time: 上午8:02
 */

/*
 * PHP获取某年第几周德起始终止日期
 */
function GetWeekDate($week,$year){
    $timestamp = mktime(0,0,0,1,1,$year);
    $dayofweek = date("w",$timestamp);
    if( $week != 1)
        $distance = ($week-1)*7-$dayofweek+1;
    $passed_seconds = $distance * 86400;
    $timestamp += $passed_seconds;
    $firt_date_of_week = date("Ymd",$timestamp);
    if($week == 1)
        $distance = 7-$dayofweek;
    else
        $distance = 6;
    $timestamp += $distance * 86400;
    $last_date_of_week = date("Ymd",$timestamp);
    return array($firt_date_of_week,$last_date_of_week);
}