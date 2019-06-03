<?php session_start();
//ajax実行後に取得するセッション変数
   // echo "{$_SESSION['manseki']}|{$_SESSION['limit']}|{$_SESSION['maxNinzu']}";
    echo "var manseki=[{$_SESSION['manseki']}];\n";
    echo "var limit=[{$_SESSION['limit']}];\n";
    //コースと日付を選んだら人数上限がある;
    echo "var maxNinzu={$_SESSION['maxNinzu']};\n";