<?php
header("Content-type: text/html; charset=UTF-8");

if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])
   && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
  // Ajaxリクエストの場合のみ処理する

  if(!empty($a= htmlspecialchars($_POST['email'],ENT_QUOTES))){
    include "connect.php" ;
    $dbh = connectDB( "yyk" );
	$sql = "SELECT count(mail) , kokyakuID ,pswd FROM kokyak 
            WHERE mail = '$a'";
	$sth = $dbh->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll();  // int(1) 
    $result_arr = $result[0];	
    if ( $result_arr["count(mail)"] == 1 ) { 
        if(!empty($result_arr['pswd']) ){
                    ///パスワードがカラではないのでログインを促す
                    echo "このメールアドレスは会員登録済みです。\n
                    ログインするか、パスワードをリセットしてください";
                }
            }  
  }else{
      echo 'The parameter of "request" is not found.';
  }
}