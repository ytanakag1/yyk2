<?php  // kakunin.php
 session_start(); // セッションを使う場合に文字出力より前で宣言
    include "mojifillter.php";
	    if ( @$_POST['token'] !=  $_SESSION['sid'] ) {
	    	ex();  // フォームから送信されたトークンがサーバーと一致しなければ中断
	    }

$_SESSION['yoyaku_post']= array(); // 入れる前に空にする
?>


<?php
include "header.php";

	if ( empty($_POST['kibobi']) ) { 
		ex('ご希望日時がありません');
	}else{
		$kibobi = h($_POST['kibobi']);
 		$_SESSION['yoyaku_post'] += ['ご希望日時' => $kibobi ];
 			//セッション配列に キーと値のセットを追加 加算代入でできる
             //希望時間がNULL→ユーザー、string(0)→店舗予約
             if(isset($_POST['kibojikan'])){ //店舗予約なら
                if( empty($_POST['kibojikan'])){ //
                    ex('ご希望日時がありません');
                }else{
                    $kibobi .= ' ' .h($_POST['kibojikan']);
                    $_SESSION['yoyaku_post']['ご希望日時'] = $kibobi ;
                }
            }
    }

	if ( !isset($_POST['cource']) || !is_numeric($_POST['cource'])) {
		ex('選択コースがありません');
	}else{
		$cource = h($_POST['cource']);
 		$_SESSION['yoyaku_post'] += ['コース名' => $cource ];
	}

	if ( empty($_POST['ninzu']) ) {
		ex('ご予約人数がありません');
	}else{
		$ninzu = h($_POST['ninzu']);
 		$_SESSION['yoyaku_post'] += ['ご予約人数' => $ninzu ];
	}

	if ( empty($_POST['email']) ) {
		ex('メールがありません');
	}else{
		$email = h($_POST['email'],0);
 		$_SESSION['yoyaku_post'] += ['メール' => $email ];
	}	

	if ( empty($_POST['tel']) ) {
		ex('お電話番号が不足しています');
	}else{
		$tel = h($_POST['tel']);
 		$_SESSION['yoyaku_post'] += ['お電話番号' => $tel ];
	}

	if ( !empty($_POST['zip'])  ) {
		$zip = h($_POST['zip'])  ;
 		$_SESSION['yoyaku_post'] += ['郵便番号' => $zip ];
	}else{
		$_SESSION['yoyaku_post'] += ['郵便番号' => "" ];
		//必須じゃないのでNULLにならないように空文字を代入しておく必要がある
	}

	if ( !empty($_POST['addr'])  ) {
		$addr = h($_POST['addr'])  ;
 		$_SESSION['yoyaku_post'] += ['ご住所' => $addr ];
	}else{
		$_SESSION['yoyaku_post'] += ['ご住所' => "" ];
	}

	if ( empty($_POST['kokyakuMei'])   ) {
		ex('お名前がありません');
	}else{
		$kokyakuMei = h($_POST['kokyakuMei'])  ;
 		$_SESSION['yoyaku_post'] += ['お名前' => $kokyakuMei ];
	}

	if ( !empty($_POST['kokyakuMei-furigana'])  ) {
		$kokyakuMeifurigana = h($_POST['kokyakuMei-furigana'])  ;
 		$_SESSION['yoyaku_post'] += ['フリガナ' => $kokyakuMeifurigana ];
	}else{
		$_SESSION['yoyaku_post'] += ['フリガナ' => "" ];
	}

	if ( !empty($_POST['comment'])  ) {
		$comment = h($_POST['comment'])  ;
 		$_SESSION['yoyaku_post'] += ['お問合わせ' => $comment ];
	}else{
		$_SESSION['yoyaku_post'] += ['お問合わせ' => "" ];
	}


	if ( !empty($_POST['kaiinkibo']) ) {
		// ☑している
		if(!empty($_POST['password']) && !empty($_POST['password_confirm'])){
			// 入力はされている
			if($_POST['password']== $_POST['password_confirm']){
		 				// 一致している
						 $password = h($_POST['password']) ;
				 		$_SESSION['yoyaku_post'] += ['パスワード' => $password ];
					}else{ 	ex("パスワードが一致しません!");	}
	 	
				}else{ ex("会員登録のチェックをした場合はパスワードが必要です!"); }
			}else{
				$_SESSION['yoyaku_post'] += ['パスワード' => "" ];
			}
			
			if ( !empty($_POST['yoyakuID']) ) {
				$_SESSION['yoyaku_post'] += ['yoyakuID' => h($_POST['yoyakuID']) ];
			}
			if ( !empty($_POST['kokyakuID']) ) {
				$_SESSION['yoyaku_post'] += ['kokyakuID' => h($_POST['kokyakuID']) ];
			}
	  	

function ex($m = ""){
	if (!empty($m)){
		exit("<h2>$m</h2><a href='./'>こっちからちゃんと送って</a>");

	}else{
		exit("<h2>必須がないか不正な処理ぽいので中断しました</h2><a href='./'>こっちからちゃんと送って</a>");
	}
}

?>  

<table>
<?php 
	foreach ($_SESSION['yoyaku_post'] as $key => $value) {
        if($key=="コース名"){	
            echo "<tr><td> $key </td><td> " . $_SESSION['cource'][$value] . "</td></tr>";
        }else{    
            echo "<tr><td> $key </td><td>$value </td></tr>";
        }
	}
?>	
</table> 
<button onclick="window.location.href ='regst.php'">この内容で送信</button>

</body></html>