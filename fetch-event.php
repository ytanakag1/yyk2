<?php
    require_once "connect.php";
    $dbh =connectDB('yyk');
    $json = array();
    
    $sqlQuery = "SELECT yk.`yoyakuID`,`kokyakuMei` ,(`yoyakuji` + INTERVAL 1 HOUR ) AS `end` ,`yoyakuji` as start
    ,yk.ninzu, cs.category, cs.courseID, k.mail,k.tel, k.zip , k.addr ,k.kokyakuID
       FROM  yoyak yk 
       LEFT JOIN course cs ON yk.courceID = cs.courseID
     LEFT JOIN kokyak k ON yk.kokyakuID = k.kokyakuID
       ORDER BY `yoyakuji`,cs.category";

    $result = pdoexecute($sqlQuery);
    $eventArray = array();
   
    foreach ( $result as $row ) {
        $row['title']= date("H:i",strtotime($row['start'])). ','. $row['ninzu'].'名様';
            $mail = explode(',',$row['mail']) ;
            if( count( $mail ) === 2  ){
                $row['mail'] = $mail[1];
            }
        array_push($eventArray, $row);
    }

    // mysqli_close($conn);
    echo json_encode($eventArray);
    // var_dump($eventArray);
?>