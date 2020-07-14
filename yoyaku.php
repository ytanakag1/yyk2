<?php  
  session_start(); // セッションを使う場合に文字出力より前で宣言
  ini_set('display_errors', "On");
  require_once "./connect.php";
?>  
<!DOCTYPE html><html lang="ja"><head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>予約カレンダー 店舗間利用</title>
<link rel="stylesheet" href="fullcalendar/fullcalendar.min.css" />
<link rel="stylesheet" href="css/carendar.css">

	<link rel="stylesheet" type="text/css" charset="UTF-8" href="./datetimepicker/jquery.datetimepicker.css">
  <link rel="stylesheet" href="css/style.css" />
<style>.modalClose{    
    display: block;width: 1.5em;height: 1.5em;position: absolute;top: 0;right: 0;color: #000;border: 1px solid;text-decoration: none;}
   .modal .inner {    box-shadow: 1px 2px 5px #aaa; width: 38em;text-align: left;}
</style>
<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>

    <script src="fullcalendar/lib/jquery.min.js"></script>
    <script src="fullcalendar/lib/moment.min.js"></script>
    <script src="fullcalendar/fullcalendar.min.js"></script>
</head><body>
  <header>
    <div class="acount">
      <?php 
        if(isset($_SESSION['ninsho'])){
          echo "ようこそ",$_SESSION['ninsho']['kokyakuMei'] ,"さん ",'<a href ="logoff.php">ログアウト</a>' ;
        }else{
          echo "<a href='login.php'>ログイン</a>";
        }
      ?>
    </div>
  </header>
  <div class="modal" id="modal02">
    <div class="overLay modalClose"></div>
    <div class="inner">
<?php  //入力フォームがajaxで呼び出される
	include 'js/form.1.php';
?>    
    <a href="" class="modalClose">✕</a>
    </div>
  </div>

        <div id="dialog">
          <div class="dialog-in">
            <button id="update">編集する</button>
            <button id="delete">削除する</button>
            <button id="cancel">キャンセル</button>
          </div>
        </div>


    <h2>予約カレンダー 店舗間利用</h2>
        <div class="response"></div>
        <div id='calendar'></div>

<script src="js/calendar.1.js"></script>
</body>
</html>