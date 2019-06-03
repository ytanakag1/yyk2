<?php
require_once "connect.php";
$dbh =connectDB('yyk');

$id = (int)$_POST['id'];
//予約テーブルから削除する
$sql = "DELETE  FROM `yoyak` WHERE yoyakuID = ?";
  $stmt =  pdoexecute($sql,$id);
  $count = $stmt->rowCount();
   echo $count //mysqli_affected_rows($conn);
// mysqli_close($conn);
?>