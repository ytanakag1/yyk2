<?php session_start();
//ajax実行後に取得するセッション変数
   // echo "{$_SESSION['manseki']}|{$_SESSION['limit']}|{$_SESSION['maxNinzu']}";
   echo isset($_SESSION['manseki']) ? "manseki=[{$_SESSION['manseki']}];\n" : '';
 
   echo isset($_SESSION['limit']) ? "limit=[{$_SESSION['limit']}];\n" :'';
   
   //コースと日付を選んだら人数上限がある;
   echo isset($_SESSION['maxNinzu']) ? "maxNinzu={$_SESSION['maxNinzu']};\n" : '';
