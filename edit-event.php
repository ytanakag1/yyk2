<?php
require_once "connect.php";
$dbh =connectDB('yyk');

// var_dump($_POST);exit;
$yoyakuID = $_POST['yoyakuID'];
$title = $_POST['title'];
$start = $_POST['start'];

$titleArr = explode(',',$title);
$sql = "UPDATE yoyak
        SET yoyakuji='" . $start . "'
        WHERE yoyakuID=" . $yoyakuID ;

$stmt =  pdoexecute($sql,$yoyakuID);
$count = $stmt->rowCount();
   echo $count ;
?>