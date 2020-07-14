<?php
   session_start();
   require_once "db.php";

   $eventArray = array();
   $disable[0] = array();
   $disable[1] = array();
   $limit[0] = array();
   $limit[1] = array();
   $dlarr=array('昼','夜');
   $max_table = 5;
   $max_seat = 4;
    $json = array();
    //$sqlQuery = "SELECT * FROM tbl_events ORDER BY start";
    $sqlQuery = "SELECT tv.`yoyakuID`, CAST(`start` AS DATE) AS `date` ,
        SUM(yk.ninzu) AS  nin ,cs.category
        FROM tbl_events as tv
        LEFT JOIN yoyak yk ON tv.yoyakuID = yk.yoyakuID
        LEFT JOIN course cs ON yk.courceID = cs.courseID
        GROUP BY `date`,cs.category
        ORDER BY start,cs.category";

    $result = mysqli_query($conn, $sqlQuery);
    
    //席は5まで。1テーブル4名まで,人数max 20名 一回の予約で12人まで、lunch dinner別にカウント
    $nokori = $max_table * $max_seat - $max_seat;
    while ($row = mysqli_fetch_assoc($result)) {
        if( $row['nin'] > $nokori ){ 
            $row['title']= $dlarr[$row['category']]."満席です"; 
            array_push($eventArray, $row);
            //カテゴリ別に2つ、日付だけの配列を作成 disable[1]=['2018-10-12','']
            array_push($disable[$row['category']], $row['date']);
            
        }elseif( $row['nin'] >= $nokori * 2 + 1 ){ //予約13以上なら
            $row['title']= $dlarr[$row['category']]."残り1席です"; 
            array_push($eventArray, $row);
            array_push($limit[$row['category']], array($row['date']=>$row['nin'])); // 予約人数上限
            
        }elseif( $row['nin'] >= $nokori * 2 ){ //予約12以上なら
            $row['title']= $dlarr[$row['category']]."残り2席です"; 
            array_push($eventArray, $row);
            array_push($limit[$row['category']], array($row['date']=>$row['nin']));  // 予約人数上限
        }
    }
    
    $_SESSION['limit']=$limit;
    
    mysqli_free_result($result);
    mysqli_close($conn);
    
    
    $_SESSION['maxNinzu']=$max_table*$max_seat;
    var_dump($_SESSION);
        
        $manseki='';
        foreach ($disable as $value) {
            $manseki.='[';
            foreach ($value as $v) {
                $ymd = explode('-',$v); //'[data-date="15"][data-month="10"]'
                $ym = (int)$ymd[1]-1;
                $manseki.= "'[data-date=\"$ymd[2]\"][data-month=\"$ym\"]'," ;
            }
            $manseki.='],';
        }
 $_SESSION['manseki']=$manseki;
 
 $limit='';
 foreach ($_SESSION['limit'] as $ld => $value) { // [0][1] lunch, dinner
    $limit.='[';
    foreach ($value as $v) {    //[0]=>[date.nin]
        foreach ($v as $k=>$n) $limit.= "{\"$k\":$n}," ;   // date=>nin
    }
    $limit.='],';
  }
  $_SESSION['limit']=$limit;

    echo json_encode($eventArray);