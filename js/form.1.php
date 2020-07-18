<?php  //html
if(isset($_SESSION['ninsho'])){
	$ninsho= $_SESSION['ninsho'];
	echo "<script>var email = '", $ninsho['mail'] ,"'</script>";
}
?>

<form name="fm" id="fm" action="kakunin.php" method="post" onsubmit="return formVlidation();">

<div> お時間
	<input type="radio" name="jikan" id="kibojikan-lunch" autocomplete="off" value="lunch"> 
		<label for="kibojikan-lunch">ランチ</label>

	<input type="radio" name="jikan"  id="kibojikan-dinner" autocomplete="off"  value="dinner">
		<label for="kibojikan-dinner">ディナー</label>
</div>

<div><i>必須</i><label for="kibobi">ご予約日時</label>
	<input type="text" name="kibobi" id="kibobi"  size="16" autocomplete="off" readonly required /> 
	<input type="text" name="kibojikan" id="kibojikan" placeholder="時間を選んでください" size="12" autocomplete="off" readonly required /> 
</div>

<div id="courceBox"><i>必須</i> コース
	<select name="cource" id="cource"  disabled="">
		<option id="defop" value="" selected>ランチかディナーを選んでください｡
	
		<?php 
			$dbh = connectDB('yyk');
				// データベースから コース名を持ってくるのでsql文を発行する
			$sql='SELECT * FROM course WHERE category >=?';
			$stmt = pdoexecute($sql,0);   // ユーザ定義関数(自作)

			$_SESSION['cource']	= array();
			foreach ($stmt as  $value) {
				$_SESSION['cource'][] = $value["courseMei"];
				echo "<option class='{$value["category"]}' value='{$value["courseID"]}'>{$value["courseMei"]}";
			}     // htmlのオプションタグを生成しているとこ
		?>
	
	</select> 
	 <div class="balloon"><strong>!</strong>コースを選んでください</div>

</div>  

<div><i>必須</i><label>ご予約人数</label>
	<input type="number" name="ninzu" id="ninzu" max="12" min="1" required > 名様
	<div class="balloon"><strong>!</strong>有効な値を入力してください｡<br>有効な値として近いのは1と2です｡</div>
</div>

<div><i>必須</i><label>メール</label>
<input type="email" name="email" id="email" value="<?php echo isset($ninsho['mail'])?$ninsho['mail']:''?>" required >
	<!-- ログインしていればここに値がでる｡@はしていない場合に警告が出ない用にするため -->
	<div class="balloon"><strong>!</strong>メールアドレスの書式が違います</div>
	<div class="balloon"><strong>!</strong>このメールアドレスは会員登録済みです。ログイン
                 <br> するか、パスワードをリセットしてください</div>
</div>

<div><i>必須</i><label>TEL</label>
	<input type="tel" name="tel"  id="tel" size="12" maxlength="12" value="<?php echo isset($ninsho['tel'])?$ninsho['tel']:''?>" required >
	<div class="balloon"><strong>!</strong>電話番号の書式が違います</div>
</div>

<div><label>〒</label>
	<input type="text" name="zip" id="zip" maxlength="7" value="<?=@$ninsho['zip']?>"  onkeyup="AjaxZip3.zip2addr(this,'','addr','addr');">
</div>

<div><label>ご住所</label>
	<input type="text" name="addr" id="addr" size="55" value="<?php echo isset($ninsho['addr'])?$ninsho['addr']:''?>" >
</div>

<div>
	<i>必須</i><label>姓名:</label><input type="text" name="kokyakuMei" id="kokyakuMei" value="<?php echo isset($ninsho['kokyakuMei'])?$ninsho['kokyakuMei']:''?>" required >
	セイメイ: <input type="text" name="kokyakuMei-furigana" id="kokyakuMei-furigana" value="<?php echo isset($ninsho['kokyakuHuri'])?$ninsho['kokyakuHuri']:''?>">
</div>

<div><textarea name="comment" id="comment" cols="60" rows="5">こめんと</textarea></div>

<?php 
    if(empty($ninsho['mail'])){
        //ログインしていたら会員登録は出ない
?>
    <div>会員登録希望<input type="checkbox" id="kaiinkibo" name="kaiinkibo"></div>
    <div id="pswd_wrqap">
            <i>必須</i>パスワードを入力 <input type="password" name="password" id="password" onautocomplete="off" value="">
            <div class="balloon"><strong>!</strong>パスワードの書式が違います<br>
            こんなかんじで入れてください 例:kpyh9848</div>
            
        <br> <i>必須</i>パスワードを確認 <input type="password" name="password_confirm" id="password_confirm">
            <div class="balloon"><strong>!</strong>パスワードが一致しません</div>
    </div>

<?php 
    } //isset End
     // CSRF対策
	$_SESSION['sid']=token(); // サーバーが保存しているセッションID(通信が成立した時点で個別に与えられる情報,)を受け取る 
?>
	<input type="hidden" name="token" value="<?=$_SESSION['sid']?>">
	<p> <input type="submit" id="soshin" value="確認へ" ></p>
</form>
<script src="./datetimepicker/jquery.datetimepicker.js" charset="UTF-8"></script>
<script src="autokana-master/jquery.autoKana.js" charset="UTF-8"></script>
<script src="js/form_validate.1.js" charset="UTF-8"></script>
<script>

</script>
