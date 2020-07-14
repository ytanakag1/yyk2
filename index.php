<?php  
	session_start(); // セッションを使う場合に文字出力より前で宣言
	require_once "./connect.php";
	include "header.php";

	if (isset($_SESSION['ninsho']))    // ログインしているか?
		 $ninsho= $_SESSION['ninsho'];
		 
	 

?>
<div class="container">

<?php 
    if(empty($ninsho['kokyakuMei'])){
        //ログインしていたら会員登録は出ない
?>
会員登録されている場合は<a href="./login.php">ログイン</a>してください
    <?php }else{
        echo "<h4>ようこそ {$ninsho['kokyakuMei']} 様 <a href='logoff.php'><smale>ログオフ</smale></a></h4>";
	} 
	
	include 'js/form.php';
	?>


	<h2>空き席状況</h2>
	<div class="response"></div>
		<div id='calendar'></div>

<script src="js/form_validate.js" charset="UTF-8"></script>
</div> <!-- contaienr -->
</body></html>