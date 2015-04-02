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

/**
 * PHP如何返回某一天是属于这个月中的第几周？
 */
function weekNumber($timestamp = '') {
    $timestamp = empty($timestamp) ? time() : $timestamp;
    return date("W", $timestamp) - date("W", strtotime(date("Y-m-01", $timestamp))) + 1;
}


/**
 * 判断是否ajax请求
 */

function isAjax(){
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
        if('xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])){
            return true;
        }
    }

    //下面是thinkphp判断ajax
    if(!empty($_POST[C('VAR_AJAX_SUBMIT')]) || !empty($_GET[C('VAR_AJAX_SUBMIT')])){
        return true;
    }
}

    /**
     * 处理文章列表,对文章按时间进行排序
     */
    private function array_sort_by_sub_field($article_list,$order_field = 'pub_time'){
        $sort = array(
            'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
            'field'     => $order_field,       //排序字段
        );
        $arrSort = array();
        foreach($article_list AS $uniqid => $row){
            foreach($row AS $key=>$value){
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if($sort['direction']){
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $article_list);
        }

        return $article_list;
    }
