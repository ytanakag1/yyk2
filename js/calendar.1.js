
$(document).ready(function () {
    var calendar = $('#calendar').fullCalendar({
        editable: true,
        events: "fetch-event.php",
        displayEventTime: false,
        eventRender: function (event, element, view) {
            if (event.allDay === 'true') {
                event.allDay = true;
            } else {
                event.allDay = false;
            }
        },
        selectable: false,
        selectHelper: false,
      
        select: function (start, end, allDay) {  //クリックイベント
         
            $(".inner").css("animation","modal 0.5s forwards");
            $('#modal02').fadeIn();
            
                var start = $.fullCalendar.formatDate(start, "Y-MM-DD");
                var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
                $("#kibobi").val(start);
            return false;
        },
        
        editable: false,
        // eventDrop: function (event, delta) {
        //     var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
        //     var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
        //     $.ajax({
        //         url: 'edit-event.php',
        //         data: 'title=' + event.title + '&start=' + start + '&yoyakuID=' + event.yoyakuID + '&id=' + event.id,
        //         type: "POST",
        //         success: function (response) {
        //             console.log(response);
        //             displayMessage("Updated Successfully");
        //         }
        //     });
        // },
        eventClick: function (event) {
            $('#delete').click(function(){
                $.ajax({
                    type: "POST",
                    url: "delete-event.php",
                    data: "&id=" + event.yoyakuID,
                    success: function (response) {
                        if(parseInt(response) > 0) {
                            // $('#calendar').fullCalendar('removeEvents', event.id);
                            displayMessage("Deleted Successfully");
                            $("#dialog").hide(100);
                            location.reload();
                        }
                    }
                })
            });
            
            $('#update').click(function(){
                $(".inner").css("animation","modal 0.5s forwards");
                $('#modal02').fadeIn();
                $("#dialog").hide(300);
                    if(event.category==0){
                        $("#kibojikan-lunch").trigger('click').prop('checked',true);
                    }else{
                        $("#kibojikan-dinner").trigger('click').prop('checked',true);
                    }
                    var dtmh = event.start._i.split(' ');
                    $('#kibobi').val(dtmh[0]);
                    $('#kibojikan').val(dtmh[1]);
                    $('#cource').val(event.courseID);
                    $('#ninzu').val(event.ninzu);
                    $('#email').val(event.mail);
                    $('#tel').val(event.tel);
                    $('#zip').val(event.zip);
                    $('#addr').val(event.addr);
                    $('#kokyakuMei').val(event.kokyakuMei);
                    $('#fm').append('<input type="hidden" value="'+event.yoyakuID+'" name="yoyakuID">');
                    $('#fm').append('<input type="hidden" value="'+event.kokyakuID+'" name="kokyakuID">');
                    // $('#comment').focus();
                });
            $("#dialog").show(300);
        } //click end
    });


    $('#cancel').click(function(){
        $("#dialog").hide(300);
    });
});

function displayMessage(message) {
	    $(".response").html("<div class='success'>"+message+"</div>");
    setInterval(function() { $(".success").fadeOut(); }, 1000);
}


$(function(){
	$(".modalOpen").click(function(){
		
	});
	
	$(".modalClose").click(function(){
      var parentsID = $(this).parents(".modal").attr("id");
      if(parentsID === "modal02") {
        $(this).parents(".modal").children(".inner").css("animation","modalClose 0.5s forwards");
      }
      $(this).parents(".modal").fadeOut();
      $(".modalOpen").removeClass("open");
      return false;
	});  
    
});