<?php  // login.php
 session_start();

    include "./connect.php" ;
    include 'header.php';
	$dbh = connectDB( "yyk" );

if ( isset($_GET['confirm']) ) {   // 送信メールのリンクを踏んでいる
		$sql = "SELECT count(mail) , `kokyakuMei`, `kokyakuHuri`, `mail`, `tel`, `zip`, `addr`
		 FROM kokyak 
		 WHERE token = '{$_GET['confirm']}'";
		$sth = $dbh->prepare($sql);
		$sth->execute();

			$result = $sth->fetchAll();  // int(1) 
			$result_arr = $result[0];

        // Stringの1か0 //Fetch ASSOKなので添字はフィールド名
		if ($result_arr["count(mail)"] == 1) {
            $email_arr = explode(",",$result_arr['mail']);
    //認証されないまま放置されたtokenで見つかったmailには
    //おなじmailがすでに登録されてないか   
             $sql = "SELECT count(mail) FROM kokyak 
                WHERE mail= ?";
                $sth = $dbh->prepare($sql);
                $sth->bindParam(1, $email_arr[1], PDO::PARAM_STR);
                $sth->execute();
                    $chkRresult = $sth->fetchAll();  // int(1) 
                    $chkResult_arr = $chkRresult[0];
                
          //      var_dump( $chkResult_arr["count(mail)"] , $result_arr['mail']);
                    if ( $chkResult_arr["count(mail)"] == 0) {
                       $sql="UPDATE kokyak
                            SET mail= ?
                                , token = ''
                            WHERE token = ? " ;
                        
                        $sth = $dbh->prepare($sql);
                        // DBにはハッシュ化した方だけ入れる
                        $sth->bindParam(1, $email_arr[1], PDO::PARAM_STR);
                        $sth->bindParam(2, $_GET['confirm'], PDO::PARAM_STR);
                        $sth->execute();
                        $_SESSION['ninsho']= $result_arr;  // セッションに個人情報格納 
                        $_SESSION['ninsho']['mail']=$email_arr[1];
                        echo '<meta http-equiv="refresh" content="5; URL=\'./index.php\'" />
                        <p>会員登録が完了しました｡ 予約ページにリダイレクト</p>';  // index.phpにリダイレクト
                   }else{
                        echo '<meta http-equiv="refresh" content="50; URL=\'./index.php\'" />';
                        echo "<p>すでにこのメールアドレスは登録済みです｡<a href='index.php'>こちら</a>から入力してください</p>";
                   }
            
			}else{
				echo '<meta http-equiv="refresh" content="5; URL=\'./index.php\'" />';
				echo "<p>会員登録が行われていません｡<a href='index.php'>こちら</a>から入力してください</p>";
			}


}elseif (!empty($_POST['soshin'])) {
            // 	ログインボタンが押されていたらここから
    $sql = "SELECT count(mail) , `kokyakuMei`, `kokyakuHuri`, `mail`, `tel`, `zip`, `addr`,pswd 
                    FROM kokyak 
                    WHERE mail = '{$_POST['email']}'";

    $sth = $dbh->prepare($sql);
    $sth->execute();
        
        $result = $sth->fetchAll();  // 2次元配列の一行データ 
        $result_arr = $result[0];   // 1次元を取り出し 

        if ($result_arr['count(mail)']==1 &&  password_verify($_POST['password'], $result_arr['pswd'] )) {
        // メールが一件ヒットして,パスワードも正しい場合
            $_SESSION['ninsho']= $result_arr;  // セッションに個人情報格納 
            echo '<p>ログインしました</p>';
            echo '<meta http-equiv="refresh" content="2; URL=\'./index.php\'" />'; 
                                    // index.phpにリダイレクト
        }else{
            echo '<p>ログインできませんでした</p>';
            echo '<meta http-equiv="refresh" content="2; URL=\'./login.php\'" />'; 
        }	
}else{
			// ログインボタンを押していない,URLパラメータもない場合はここ
	
?>
  <h3>登録会員ログイン</h3>
  <form action="" method="POST">
   <p>	メール<input type="text" name="email" id="email" value="" required ></p>
  	<p>パスワードを入力 <input type="password" name="password" id="password"></p>
  	<input type="submit" name="soshin" id="soshin" value="ログイン" >
  </form>
  <p class="forgot">
  	<a href="forgot.php">パスワード忘れはこちら</a> 
  </p>
<?php }	?>

</body></html>
