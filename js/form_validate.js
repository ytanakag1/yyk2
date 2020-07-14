 // autoKana Livelary
	$(function() { 
		$.fn.autoKana('#kokyakuMei', '#kokyakuMei-furigana', {katakana:true});
	});

	
	$(function() { //描画時に会員希望にはチェックしない
		$("#kaiinkibo").prop('checked',false);
	});
	
	
	$('#kibobi').click(function(){
			var attr = $(this).attr("readonly");
				// 希望日に readonry属性を取得してattrに代入
			if( typeof attr == 'undefined' || attr == false ){
					alert("ランチかディナーを選んでください");
			}
    });
  
  
        
	$('#kibobi').change(function(){
			var max_ninzu ='';
			var maxSheet =  maxNinzu-8;
			console.log(limit);
			$('#cource').css("border","solid 2px #3faff1");
		//予約数が12以下になったらninzuの上限を変える
			if($('input[name=jikan]:eq(0)').prop('checked')){
					var jikan = limit[0];
			}else if($('input[name=jikan]:eq(1)').prop('checked')){
					var jikan = limit[1];
			}
			
			var before_date = moment($('#kibobi').val(), "YYYY-MM-DD"); // 第一引数：日時、第二引数：フォーマット形式
			var after_date = before_date.format('YYYY-MM-DD');
					if(jikan.length > 0 ){
							
							for (let k = 0; k < jikan.length; k++) {
									const element = jikan[k];
									
									$.each(element,function(i,e){
											console.log(i + '=>' + e);
											if( i == after_date ) 
												maxSheet = maxNinzu-e
									});
							}
					}
					for(var k= 1; k <= maxSheet ;k++){
							max_ninzu +=  "<option>" + k + "</option>\n";
					}
					$('#ninzu').html(max_ninzu);
	});



    
		$('#cource').change(function(){
				if($(this).val() != ""){
						$('#cource').css("border","solid 1px #a6a5a6");
						//$('#ninzu').focus();
				}

		});
        


// ラジオボタンのランチを選んだ
var manseki = [[],[]];
var limit = [[],[]];
var maxNinzu = 20;
	$("#kibojikan-lunch").change(function(){
    var res = resession($(this).val());
	
		$("#cource").removeAttr("disabled","disabled"); // selectメニューをアクティブにする
		$("option.1").attr("hidden","hidden");      // optionにclass="1"がついてる方を隠す
		$("option.0").removeAttr("hidden","hidden");  // optionにclass="1"がついてる方を隠さない
		$("#defop").text("コースを選んでください");          // id="defop"がついた要素の中の文字列をこれに変える
	 jikanCourceReset();
 });

//ディナーを選んだ
	$("#kibojikan-dinner").change(function(){
		var res = resession($(this).val());

		$("#cource").removeAttr("disabled","disabled");
		$("option.0").attr("hidden","hidden"); 
		$("option.1").removeAttr("hidden","hidden");  
		$("#defop").text("コースを選んでください");
	 jikanCourceReset();
	});

function jikanCourceReset(){
		$('#kibobi').val("");
		$('#cource').val("");
}





function resession(selectedJikan){
    var putdata = {'request' : selectedJikan};
    var result = [];
    //ajax でSESSION取得
    $.ajax({
        type: "POST",
        url: "js/resession.php",
        data: putdata,
      }).done(function(data, dataType) {
        // PHPから返ってきたデータの表示
        $("<script>"+data+"</script>").appendTo("body");
        
        if(selectedJikan=='dinner'){
            $('#kibobi').datetimepicker({
                //DBから取得した満席情報を反映
                onGenerate:function( ct ){
								
                    for (let i = 0; i < manseki[1].length; i++) {
                        const element = manseki[1][i];
                        $(this).find(element).addClass('xdsoft_disabled');
                    }	
                },
                lang:'ja',
                minDate : '-1970/01/01',
                maxDate : '+1970/02/29',
                //timepicker:false
                allowTimes : ['17:30','18:00','18:30','19:00','19:30','20:00','20:30'],
                //step : 30
						}).attr('placeholder',"ご予約日時"); 
						
        }else if(selectedJikan=='lunch'){
            $('#kibobi').datetimepicker({
                //DBから取得した満席情報を反映
                onGenerate:function( ct ){
                    for (let i = 0; i < manseki[0].length; i++) {
                        const element = manseki[0][i];
                        $(this).find(element).addClass('xdsoft_disabled');
                    }	
                },
                lang:'ja',
                minDate : '+1970/01/01',
                maxDate : '+1970/01/31',
                //timepicker:false
                allowTimes : ['11:30','12:00','12:30','13:00','13:30'],
            }).attr('placeholder',"ご予約日時");
        }
            

      }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
        // エラーメッセージの表示
        alert('Error : ' + errorThrown);
      });
}






// emailを入力したら先に問い合わせる
    $('#email').change(function(){
        var submitData = {'email' : $('#email').val()};
        $.ajax({
            url: 'email-verify.php',
            data: submitData,
            type: "POST",
            success: function (data) { //data→phpが書き出した文字列      
                if(data.length > 60){
                   $("#email").focus().next().next().show();
                   setTimeout( function(){ 
                       $("#email").next().next().hide(500);
                       window.location.href = 'login.php';
                    },3000) ;
                }  
             }
        });
    });




	$('#kaiinkibo').change(function(){
		if($(this).prop('checked')){
			$('#pswd_wrqap').show(200);
			$('[type="password"]').attr("required","required");
		}else{
			
			$('#pswd_wrqap').hide(200);
			$('[type="password"]').removeAttr("required");
		}
	});

// バリデーションチェック｡ 最初に必須項目は全てfalseを代入して送信出来ないようにする
	 var ninzu = false;
	 var tel1 = false;  var tel2 = false;  var tel3 = false; 
		var email = false; var kibobi =false; var cource = false;


 function errAlert(sp){ // バルーン式に開閉するユーザ定義関数
		sp.focus().next().show();
			setTimeout( function(){ sp.next().hide(500);
			 },3000) ;
 }


 function ninzuCheck(sp){  // 人数は1~12までの整数のみ
		if( sp.match(/^\d{1,2}$/)  ){
			if(sp <= 12 && sp > 0 ){
				 return true;       // 条件を満たしたらtrueを返す
			}else{
					return false;
			} 
		}else{
			errAlert($("#ninzu"));   // 満たさないなら自作のアラート
			return false;
		}
 }

 
 function emailCheck(sp){   // email書式が正しいか
		if( sp.match(/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])*\.+([a-zA-Z0-9\._-]+)+$/) ){
			return true;
		}else{
			 errAlert($("#emal"));   // アラート
			return false;
		}
 } 

 function telCheck(sp){  // 3~4桁の整数のみ
		if(  sp.match(/^\d{10,12}$/) ){
			 return true;
		}else{
			 errAlert($('#tel'));   // アラート
			return false;
		}
 }


function checkPassword( sp ) {
	if( !$('#kaiinkibo').prop('checked') ||
		sp.match( /(?=.{4,5})(?=.*\d+.*)(?=.*[a-zA-Z]+.*).*/ ) ) {
		return true;
	} else {
		 errAlert($("#password"));   
		return false;
	}
}


 function kibobiCheck(sp){   
		if( sp ){
			 return true;
		}else{
			 errAlert($('#kibobi'));    
			return false;
		}
 }

 function courceCheck(sp){  // 3~4桁の整数のみ
		if( sp ){
			 return true;
		}else{
			 errAlert($('#cource'));   // アラート
			return false;
		}
 }


 function formVlidation(){  
	var password = true;   // はじめにtrueで初期化しておく
	var returnFlag = [];
	// 送信ボタンで呼び出し｡ データ要件を満たすべきリスト
	 var sp = $("#kibobi").val();
		returnFlag['kibobi'] = kibobiCheck(sp); // この関数の戻り値が代入される

	 var sp = $('#cource').val() ;
		returnFlag['cource'] = courceCheck(sp);

	 var sp = $("#ninzu").val();
		returnFlag['ninzu'] = ninzuCheck(sp);

	 var sp = $("#email").val();  
		returnFlag['email'] = emailCheck(sp);
	 
	 var sp = $("#tel").val();  
		returnFlag['tel'] = telCheck(sp);

		 if ($("#kaiinkibo").prop('checked')){
			// 会員希望が onなら
			 var sp = $("#password").val();
					if (sp == $('#password_confirm').val()){
						password = checkPassword(sp) ;
					}else{
						errAlert($("#password_confirm"));   // アラート
						password = false;
					}
			}else{
				$("#password").val('');
			}


//  最終チェック
		 for(key in returnFlag){
					console.log(key + " => " +returnFlag[key]);
				if(returnFlag[key] != true ){
						var sp = '#' + key;
					  errAlert($(sp));
						return false;
				}
		 }
			if(password){
				return true;  //roopを抜けてからfalseがなければ
			}else{
			  return false;
			}
 }
 
 

