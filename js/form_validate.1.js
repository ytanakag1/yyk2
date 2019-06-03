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

// ラジオボタンのランチを選んだ
	$("#kibojikan-lunch").change(function(){
		$('#kibojikan').datetimepicker({
			datepicker:false,
			format:'H:i',
			allowTimes : ['11:30','12:00','12:30','13:00','13:30'],
		});
		
		$("#cource").removeAttr("disabled","disabled"); // selectメニューをアクティブにする
		$("option.1").attr("hidden","hidden");      // optionにclass="1"がついてる方を隠す
		$("option.0").removeAttr("hidden","hidden");  // optionにclass="1"がついてる方を隠さない
		$("#defop").text("コースを選んでください");          // id="defop"がついた要素の中の文字列をこれに変える
			jikanCourceReset();
			// $('#kibojikan').focus();
	});
	
	$("#kibojikan-dinner").change(function(){
		$('#kibojikan').datetimepicker({
			datepicker:false,
			format:'H:i',
			allowTimes :  ['17:30','18:00','18:30','19:00','19:30','20:00','20:30'],
		});
	
		$("#cource").removeAttr("disabled","disabled");
		$("option.0").attr("hidden","hidden"); 
		$("option.1").removeAttr("hidden","hidden");  
		$("#defop").text("コースを選んでください");
			jikanCourceReset();
			// $('#kibojikan').focus();
	});

function jikanCourceReset(){
	//	$('#kibobi').val("");
		$('#cource').val("");
}

// emailを入力したら先に問い合わせる
	if( email == undefined)
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
 
 

