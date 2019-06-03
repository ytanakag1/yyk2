<?php session_start();	///06_php/yyk/regst.php
 ini_set('display_errors', "On"); 
if ( empty( $_SESSION['yoyaku_post'] )) {  //セッションがなければ
	//	echo '<meta http-equiv="refresh" content="5; URL=\'./yoyaku.php\'" />';		// htmlのメタタグで5秒でリダイレクトさせる
		echo "<a href='./yoyaku.php'>フォーム</a>から正しく入力してください";
		exit();  // ここで中断
	} 

  $yoyaku_post = $_SESSION['yoyaku_post']; // ローカル変数に代入
  //var_dump($yoyaku_post);exit();
//会員希望かどうか $kibo\
	$kibo = empty($yoyaku_post['パスワード']) ? 0 :1 ;

  if ( $kibo ){
	  // 希望パスワードがある場合
	  $options = array('cost' => 10); //ハッシュ化の計算コストを指定
	  // DB に入れる文字列が $hash
	  $hash = password_hash( $yoyaku_post['パスワード'] , PASSWORD_DEFAULT, $options );
  }else{
  	$hash = '';
  }

  include "connect.php" ;
	$dbh = connectDB( "yyk" );
		//未ログインなので、メールアドレスで検索して ヒットした行数と,顧客IDを取得
	$sql = "SELECT count(mail) , kokyakuID ,pswd FROM kokyak 
			WHERE mail = '{$yoyaku_post['メール']}'";
	$sth = $dbh->prepare($sql);
	$sth->execute();
		
	//	$result = $sth->fetchColumn(0);  // int(1) 
	//	$kokyakuID = $sth->fetchColumn(1);  // int(1) 
		$result = $sth->fetchAll();  // int(1) 
			$result_arr = $result[0];		//取得結果の0行目を取り出して代入


    // try {			// 例外的エラーを捕捉して意味不明なエラーを出さない
     $dbh->beginTransaction();	// トランザクションの開始

		 $token = token();
		 
			if( !empty($_SESSION['yoyaku_post']['パスワード'])){
					$email = $token.','.$yoyaku_post['メール'];
				}else{
					$email = $yoyaku_post['メール'];
			}
			
 			if ( $result_arr["count(mail)"] == 0 && empty($_SESSION['yoyaku_post']['yoyakuID']) ) {
				  // 新規客ならmailの登録がないので追加する && yoyakuIDがあったら更新
 					
						 //仮登録するので,トークンと結合してカンマ区切りで入れる｡
				$p = 0;		 
 				$sql = "INSERT INTO `kokyak`( `kokyakuMei`, `kokyakuHuri`, `mail`, `tel`, `zip`, `addr`, `pswd`,token) 
				 VALUES (?,?,?,?,?,?,?,?)";
				 $kokyakuID='';
				 $kokyakuID = kokyakuBind($dbh,$sql,$yoyaku_post,$kokyakuID,$email,$hash,$token);
						
			}else{  
					// リピータなら (メールで一件ヒットしている場合)
					$kokyakuID = isset( $result_arr["kokyakuID"] ) ? $result_arr["kokyakuID"] : $yoyaku_post['kokyakuID'];
					// 匿名希望だと思うので全部上書きする
					$sql="UPDATE `kokyak` SET `kokyakuMei` = ?, `kokyakuHuri` = ? ,`mail` = ?, `tel` = ? ,`zip` = ?, `addr` = ?, `pswd` = ? ,`token` = ?
						WHERE `kokyak`.`kokyakuID` = ?";
					kokyakuBind($dbh,$sql,$yoyaku_post,$kokyakuID,$email,$hash,$token);
			}

		 if( empty($_SESSION['yoyaku_post']['yoyakuID'])  ){
		
			 $sql = "INSERT INTO `yoyak`( `yoyakuji`, `ninzu`, `courceID`, `kokyakuID`, `goyobo`) VALUES(?,?,?,?,?)";
				$stmt = yoyakuBind($dbh,$sql,$yoyaku_post,$kokyakuID);
				 $yoyakuID = $dbh->lastInsertId('yoyakuID'); // 予約ID取得
			
			}else{
				$sql = "UPDATE `yoyak` SET `yoyakuji` = ? , `ninzu` = ? , `courceID` = ? , `kokyakuID` = ?, `goyobo` = ? 
				WHERE yoyakuID = " . $_SESSION['yoyaku_post']['yoyakuID'];
					$stmt = yoyakuBind($dbh,$sql,$yoyaku_post,$kokyakuID );
			}	 				

		// carendarに書き込む
			 $eventTitle = $yoyaku_post['お名前'];
				$start_date = date("Y-m-d H:i:s",strtotime($yoyaku_post['ご希望日時']));
				$end_date = date("Y-m-d H:i:s",strtotime('+1 hour',strtotime($start_date)));
			 	
      //コミット
      $dbh->commit();
  //  }catch(PDOException $e){
  
  //   $dbh->rollback();
  //   echo "データベースに接続時エラーが発生しました";
  // }     
      $to = $yoyaku_post['メール'] ;   //宛先メールアドレス
      $url = 'https://'.$_SERVER["HTTP_HOST"] ;
      // 送信元
      mb_language("ja");
      mb_internal_encoding("UTF-8");
	  $mailFrom = "From: " ;
	  $from_mail = 'ginzo@ultimai.org';
	  // 送信者情報の設定
	  $from = "出雲川開発 ";
	  $header = '';
	  $header .= "Content-Type: text/plain; charset=\"ISO-2022-JP\" \r\n";
	  $header .= "Return-Path: " . $from_mail . " \r\n";
	  $header .= "From: " . mb_encode_mimeheader (mb_convert_encoding($from,"ISO-2022-JP","AUTO")) . "<" . $from_mail . ">" ." \r\n";
	  //$header .= "Sender: " . $from ." \r\n";
	  $header .= "Reply-To: " . $from_mail . " \r\n";
	  $header .= "Organization: " . $mailFrom . " \r\n";
	  $header .= "X-Sender: " . $from_mail . " \r\n";
	  $header .= "X-Priority: 3 \r\n";
	  
      $message = $yoyaku_post['お名前']. "様 \nご予約ありがとうございます。";		//本文
      
        if( $kibo ){
          $subject = "=?iso-2022-jp?B?".base64_encode(mb_convert_encoding("メールアドレス認証のお知らせ","JIS","UTF-8"))."?=";
          $honbun = $message."以下のURLにアクセスすると,本登録が完了します｡\n". $url . "/mdlsrc/yyk/login.php?confirm=".$token ;
        }else{
            //$subject = "=?iso-2022-jp?B?".base64_encode(mb_convert_encoding("ご予約完了のお知らせ","JIS","UTF-8"))."?=";
			$subject = mb_encode_mimeheader("ご予約完了のお知らせ", 'ISO-2022-JP-MS');  
            $honbun = $yoyaku_post['ご希望日時'] ."に". $yoyaku_post['ご予約人数']."名様にてご予約を承りました。\nご来店を心よりお待ちしております。";
        }
            
          $honbun=mb_convert_encoding($honbun, 'ISO-2022-JP-MS');    
			   mail($to, $subject, $honbun, $header); 
    			echo $to  . "へ送信しました\n";
									echo "<P>ご予約ありがとうございます。<br>只今確認メールを送信しました。</p>
									<p><a href ='/'>Home</a>; 
			 $_SESSION=NULL ;  
			 $_POST =NULL ;	
	

			 
function yoyakuBind($dbh,$sql,$yoyaku_post,$kokyakuID){
	$p = 0;
	$stmt = $dbh->prepare($sql);
	$stmt->bindParam(++$p, $yoyaku_post['ご希望日時'], PDO::PARAM_STR);
	$stmt->bindParam(++$p, $yoyaku_post['ご予約人数'], PDO::PARAM_INT);
	$stmt->bindParam(++$p, $yoyaku_post['コース名'], PDO::PARAM_INT);
	$stmt->bindParam(++$p, $kokyakuID , PDO::PARAM_STR);
	$stmt->bindParam(++$p, $yoyaku_post['お問合わせ'], PDO::PARAM_STR);
	$stmt->execute();
	return  $dbh;
}

function kokyakuBind($dbh,$sql,$yoyaku_post,$kokyakuID,$email,$hash,$token){
	$p = 0;
	$stmt = $dbh->prepare($sql);
	$stmt->bindParam(++$p, $yoyaku_post['お名前'] , PDO::PARAM_STR);
	$stmt->bindParam(++$p, $yoyaku_post['フリガナ'] , PDO::PARAM_STR);
	$stmt->bindParam(++$p, $email, PDO::PARAM_STR);
	$stmt->bindParam(++$p, $yoyaku_post['お電話番号'], PDO::PARAM_STR);
	$stmt->bindParam(++$p, $yoyaku_post['郵便番号'], PDO::PARAM_STR);
	$stmt->bindParam(++$p, $yoyaku_post['ご住所'], PDO::PARAM_STR);
	$stmt->bindParam(++$p, $hash, PDO::PARAM_STR);
	$stmt->bindParam(++$p, $token, PDO::PARAM_STR);
	$stmt->bindParam(++$p, $kokyakuID, PDO::PARAM_INT);
	$stmt->execute();
	$kokyakuID = $dbh->lastInsertId('kokyakuID');
	return  $kokyakuID;
}