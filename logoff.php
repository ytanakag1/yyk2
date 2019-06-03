<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
$_SESSION['ninsho']= array();  // セッション破棄

            echo '<p>ログオフしました</p>';
            echo '<meta http-equiv="refresh" content="3; URL=\'./yoyaku.php\'" />'; 

