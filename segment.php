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

function is_ajax(){
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
    function array_sort_by_sub_field($article_list,$order_field = 'pub_time'){
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

/**
 * 处理文章列表,对文章按时间进行排序
 */
function array_sort_by_sub_field($article_list,$order_field = 'pub_time'){
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


/**
 * 多线程
 * @param $nodes
 * @return array
 */
function multiple_threads_request($nodes){
    if(empty($nodes)){
        return false;
    }
    $mh = curl_multi_init();
    $curl_array = array();
    $file_fp = array();
    foreach($nodes as $i => $url)
    {
        $curl_array[$i] = curl_init($url['remote']);
        //curl_setopt($curl_array[$i], CURLOPT_RETURNTRANSFER, true);

        $file_fp[$i] = fopen($url['local'],"w");
        curl_setopt($curl_array[$i], CURLOPT_FILE, $file_fp[$i]);
        curl_setopt($curl_array[$i], CURLOPT_HEADER, 0);

        curl_multi_add_handle($mh, $curl_array[$i]);
    }
    $running = NULL;
    do {
        //usleep(10000);
        curl_multi_exec($mh,$running);
    } while($running > 0);

    $res = array();
    foreach($nodes as $i => $url)
    {
        $res[$url] = curl_multi_getcontent($curl_array[$i]['remote']);
    }

    foreach($nodes as $i => $url){
        curl_multi_remove_handle($mh, $curl_array[$i]);
        fclose($file_fp[$i]); //关闭文件
    }

    
//2种中文截取无乱码
function utf8sub($str,$len){
    if($len<=0){
        return ;
    }
    $res="";
    $offset=0;
    $chars=0;
    $length=strlen($str);
    while($chars<$len && $offset<$length){
 
        $hign=decbin(ord(substr($str,$offset,1)));
            if(strlen($hign)<8){
                $count=1;
            }elseif(substr($hign,0,3)=="110"){
                $count=2;
            }elseif(substr($hign,0,4)=="1110"){
                $count=3;
            }elseif(substr($hign,0,5)=="11110"){
                $count=4;
            }elseif(substr($hign,0,6)=="111110"){
                $count=5;
            }elseif(substr($hign,0,7)=="1111110"){
                $count=6;
            }
 
        $res.=substr($str,$offset,$count);
        $offset+=$count;
        $chars+=1;
 
    }
    return $res;
}


function utf8sub1($str,$len){
    $chars=0;
    $res="";
    $offset=0;
    $length=strlen($str);
    while($chars<$len && $offset<$length){
        $hign=decbin(ord(substr($str,$offset,1)));
        if(strlen($hign)<8){
            $count=1;
        }elseif($hign & "11100000"=="11000000"){
            $count=2;
        }elseif($hign & "11110000"=="11100000"){
            $count=3;
        }elseif($hign & "11111000"=="11110000"){
            $count=4;
        }elseif($hign & "11111100"=="11111000"){
            $count=5;
        }elseif($hign & "11111110"=="11111100"){
            $count=6;
        }
        $res.=substr($str,$offset,$count);
        $chars++;
        $offset+=$count;
    }
    return $res;
}
$a="中华ah人民hdj";
echo utf8sub($a,5);


//统计字数
function str_count($text,$charset = 'UTF-8'){
    return mb_strlen(preg_replace('/\s/','',html_entity_decode(strip_tags($text)),$charset));
}



//过滤表情字
//入库前
$text = preg_replace_callback('/[\xf0-\xf7].{3}/', function($r) { return '@E' . base64_encode($r[0]);}, $text);
//出库后
$text = preg_replace_callback('/@E(.{6}==)/', function($r) {return base64_decode($r[1]);}, $text);



    /**
     * 获取分页html
     */
    private function get_pager_html($cur_page,$prefix,$total,$size = 50,$suffix = '.html'){
        $pagehtml = '';

        $total_page = ceil($total / $size);
        $cur_page = ($cur_page > $total_page) ? $total_page : $cur_page;

        if($total_page > 1){
            $prev_page = $cur_page - 1;
            $next_page = $cur_page + 1;
            if($cur_page != 1){
                $pagehtml .= "<a href={$prefix}1{$suffix}>第一页</a>";
                $pagehtml .= "<a href={$prefix}{$prev_page}{$suffix}>上一页</a>";
            }

            $loop_lt = ($cur_page >5) ? 5 :$cur_page;
            for($i = $loop_lt; $i > 1 ; $i--){
                $loop_page = $cur_page - $i + 1;
                $pagehtml .= "<a href={$prefix}{$loop_page}{$suffix}>{$loop_page}</a>";
            }
            $pagehtml.= "<span class='current'>{$cur_page}</span>";

            $c_page = $total_page - $cur_page;
            $loop_gt = ($c_page >5) ? 5 :$c_page;
            for($i = 1; $i < $loop_gt ; $i++){
                $loop_page = $cur_page + $i;
                $pagehtml .= "<a href={$prefix}{$loop_page}{$suffix}>{$loop_page}</a>";
            }

            if($cur_page != $total_page){
                $pagehtml .= "<a href={$prefix}{$next_page}{$suffix}>下一页</a>";
                $pagehtml .= "<a href={$prefix}{$total_page}{$suffix}>最后一页</a>";
            }
        }

        return $pagehtml;
    }



//去掉中英文标点符号
$keyword="好‘。，好学习PHP！！！";
$keyword=urlencode($keyword);//将关键字编码
$keyword=preg_replace("/(%7E|%60|%21|%40|%23|%24|%25|%5E|%26|%27|%2A|%28|%29|%2B|%7C|%5C|%3D|\-|_|%5B|%5D|%7D|%7B|%3B|%22|%3A|%3F|%3E|%3C|%2C|\.|%2F|%A3%BF|%A1%B7|%A1%B6|%A1%A2|%A1%A3|%A3%AC|%7D|%A1%B0|%A3%BA|%A3%BB|%A1%AE|%A1%AF|%A1%B1|%A3%FC|%A3%BD|%A1%AA|%A3%A9|%A3%A8|%A1%AD|%A3%A4|%A1%A4|%A3%A1|%E3%80%82|%EF%BC%81|%EF%BC%8C|%EF%BC%9B|%EF%BC%9F|%EF%BC%9A|%E3%80%81|%E2%80%A6%E2%80%A6|%E2%80%9D|%E2%80%9C|%E2%80%98|%E2%80%99)+/",'',$keyword);
$keyword=urldecode($keyword);//将过滤后的关键字解码
echo $keyword;
