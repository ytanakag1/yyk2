<?php
header("Content-type: text/html; charset=UTF-8");

    include "connect.php" ;
    //include 'header.php';
    include 'mojifillter.php';
    $dbh = connectDB( "yyk" );
?>
<script  src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
 <link rel="stylesheet" href="style.css" />

    
    
<?php
if ( !empty($_POST['forgot_soshin']) && !empty($_POST['email']) && empty($_POST['pswd_soshin'])) {
    $email = h( $_POST['email'] ,0);
    
    $sql = "SELECT count(mail) 
     FROM kokyak 
     WHERE mail = ? ";
        $sth = $dbh->prepare($sql);
        $sth->bindParam(1, $email, PDO::PARAM_STR);
        $sth->execute();
        
        $result = $sth->fetchAll();  // int(1) 
        $result_arr = $result[0];
        
        if ($result_arr["count(mail)"] == 1) {
            // メール登録がある
            $token = token();
            // DBに発行したトークンを書き込む
            $sql="UPDATE `kokyak` SET `token` = ?
                    WHERE `mail` = ?";
           //var_dump( $sql); exit();
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(1, $token, PDO::PARAM_STR);
                $stmt->bindParam(2, $email, PDO::PARAM_STR);
                $stmt->execute();

                
                // 念の為、言語と文字コードの設定
                $url = 'https://'.$_SERVER["HTTP_HOST"].'/mdlsrc/yyk/' ;
                mb_language("ja");
                mb_internal_encoding("UTF-8");
                $mailFrom = "From: " ;
                $from_mail = 'ginzo@ultimai.org';
                // 送信者情報の設定
               // $from = mb_encode_mimeheader("出雲川開発 ");
                $from = "出雲川開発 ";
                $header = '';
                $header .= "Content-Type: text/plain; charset=\"ISO-2022-JP\" \r\n";
                $header .= "Return-Path: " . $from_mail . " \r\n";
                $header .= "From: " . mb_encode_mimeheader (mb_convert_encoding($from,"ISO-2022-JP","AUTO")) . "<" . $from_mail . ">" ." \r\n";
                $header .= "Sender: " . $from ." \r\n";
                $header .= "Reply-To: " . $from_mail . " \r\n";
                $header .= "Organization: " . $mailFrom . " \r\n";
                $header .= "X-Sender: " . $from_mail . " \r\n";
                $header .= "X-Priority: 3 \r\n";

//メール送信

               $subject = "=?iso-2022-jp?B?".base64_encode(mb_convert_encoding("パスワード再発行URLのお知らせ","JIS","UTF-8"))."?=";
                //件名を設定（JISに変換したあと、base64エンコードをしてiso-2022-jpを指定する）
                
                $message = "パスワードリセットのURLは以下のとおりです \n";		//本文
                if( !empty($token) ) 
                "以下のURLにアクセスすると,本登録が完了します｡\n".
                $message .= $url . "forgot.php?confirmforgot=".$token ;
                // 秘密のトークンをURLパラメータとしてくっつける
                $message=mb_convert_encoding($message, 'ISO-2022-JP-MS');    
                        
                mail($email, $subject, $message, $header); 
                echo $email  . "へ送信しました";
            }else{
                //メールがないので
                echo '<meta http-equiv="refresh" content="5; URL=\'./yoyaku.php\'" />
                 <p>入力されたメールアドレスは登録がありません.<br>
                ご予約フォームへ入力して送信してください.' ;
            }
}elseif(!empty($_GET['confirmforgot']) && empty($_POST['pswd_soshin'])){
// メールのURLからトークン付きで開かれた場合
    $sql = "SELECT count(mail) 
    FROM kokyak 
    WHERE token = ? ";
    $sth = $dbh->prepare($sql);
    $sth->bindParam(1, $_GET['confirmforgot'], PDO::PARAM_STR);
    $sth->execute();

        $result = $sth->fetchAll();  // int(1) 
        $result_arr = $result[0];

        if ($result_arr["count(mail)"] == 1) {
            //1件あればトークンは正しい→ パスワード入力と上書き処理
            $token=h($_GET['confirmforgot']); // CSRF対策
?>

<h3>パスワード再発行 希望パスワードの送信</h3>
<form action="" method="post" onsubmit="return forgotVlidation()">
	<div class="pswd_wrqap">
			<i>必須</i>パスワードを入力 <input type="password" name="password" id="password" onautocomplete="off" required>
			<div class="balloon"><strong>!</strong>パスワードの書式が違います<br>
			こんなかんじで入れてください 例:kpyh9848</div>
    		<br> <i>必須</i>パスワードを確認 <input type="password" name="password_confirm" id="password_confirm" required>
			<div class="balloon"><strong>!</strong>パスワードが一致しません</div>
            <input type="hidden" name="token" value="<?=$token?>">
        <p> <input type="submit" id="pswd_soshin" name="pswd_soshin" value="パスワードを変更する" ></p>
	</div>
</form>
<script>
   // forgot password varidation
  function forgotVlidation(){  
    var sp = $("#password").val();
          if (sp == $('#password_confirm').val()){
            password = checkPassword(sp) ;
          }else{
            errAlert($("#password_confirm"));   // アラート
            password = false;
          }
    return password;
  }
  
function checkPassword( sp ) {
  if( sp.match( /(?=.{4,5})(?=.*\d+.*)(?=.*[a-zA-Z]+.*).*/ ) ) {
    return true;
  } else {
     errAlert($("#password"));   
    return false;
  }
}
 function errAlert(sp){ // バルーン式に開閉するユーザ定義関数
    sp.focus().next().show();
      setTimeout( function(){ sp.next().hide(500);
       },3000) ;
 }

</script>
<?php
        }else{
            //トークンが正しくない
            echo '<meta http-equiv="refresh" content="5; URL=\'./forgot.php\'" />
                 <p>送信されたURLは見つかりませんでした.<br>
                パスワード再発行フォームへ入力して送信してください.' ;
        }

}elseif( !empty($_POST['pswd_soshin']) && !empty($_POST['password']) ){
// kibo pswd を送っている
   $token=h($_GET['confirmforgot']); // CSRF対策
    
    if(!empty($_POST['token']) && $_POST['token'] == $token ){
        //tokenが正しい のでpswdを上書きする
        $pswd = h($_POST['password']);
         $options = array('cost' => 10); //ハッシュ化の計算コストを指定
         $pswd = password_hash( $pswd , PASSWORD_DEFAULT, $options );
          
          $sql="UPDATE `kokyak` SET `pswd` = ? , `token` = '' WHERE `token` = ?";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(1, $pswd, PDO::PARAM_STR);
            $stmt->bindParam(2, $token, PDO::PARAM_STR);
              $stmt->execute();
            echo '<meta http-equiv="refresh" content="5; URL=\'yoyaku.php\'" />
                 <p>パスワードの変更が完了しました.';    
    }


    
}else{
    // post getがない場合

?>
    <h3>パスワード再発行リンクの送信</h3>
	<form action="" method="POST">
	<p>	メール:<input type="text" name="email" id="email" value="" required ></p>
	<input type="submit" name="forgot_soshin" id="forgot_soshin" value="パスワード再設定のためのリンクを送信してください" >
	</form>
<?php } //post getがない場合END
 ?>

