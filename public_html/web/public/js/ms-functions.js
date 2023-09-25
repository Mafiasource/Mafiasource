$.ajaxPrefilter(function(options) {
    options.async = true;
});

var ajaxBusy = false;
$(document).ajaxStart( function() { 
    ajaxBusy = true; 
}).ajaxStop( function() {
    ajaxBusy = false;
});

function scrollTop() {
    window.scroll({top: 0, left: 0, behavior: 'smooth'});
}

function number_format(number, decimals, dec_point, thousands_sep) {
  number = (number + "")
    .replace(/[^0-9+\-Ee.]/g, "");
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === "undefined") ? "," : thousands_sep,
    dec = (typeof dec_point === "undefined") ? "." : dec_point,
    s = "",
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return "" + (Math.round(n * k) / k)
        .toFixed(prec);
    };
  s = (prec ? toFixedFix(n, prec) : "" + Math.round(n))
    .split(".");
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || "")
    .length < prec) {
    s[1] = s[1] || "";
    s[1] += new Array(prec - s[1].length + 1)
      .join("0");
  }
  return s.join(dec);
}

function moneyDownFlash(elm, diff){
    $(elm).hide().delay(200).html("-$"+number_format(diff, 0, "", ",")).css('color', "orangered").fadeIn(0).delay(4160).fadeOut(0);
}

function moneyUpFlash(elm, diff){
    $(elm).hide().delay(200).html("+$"+number_format(diff, 0, "", ",")).css('color', "#00FF00").fadeIn(0).delay(4160).fadeOut(0);
}

function valueDownFlash(elm, diff){
    $(elm).hide().delay(200).html("-"+number_format(diff, 0, "", ",")).css('color', "orangered").fadeIn(0).delay(4160).fadeOut(0);
}

function valueUpFlash(elm, diff){
    $(elm).hide().delay(200).html("+"+number_format(diff, 0, "", ",")).css('color', "#00FF00").fadeIn(0).delay(4160).fadeOut(0);
}

function checkMessages(receiver)
{
    if(ajaxBusy == false)
    {
        var postData = {receiver: receiver, securityToken: $("#message-container").closest("input[name=security-token]").val()};
        var formURL = "/game/messages/check";
        var method = "POST";
        $.ajax(
        {
       	    url : formURL,
       	    type: method,
       	    data : postData,
            success:function(data)
            {
                try 
                {
                  obj = JSON.parse(data);
                  if(obj.IDThen != obj.IDNow)
                  {
                      reloadMessages(receiver);
                      return true;
                  }
                }
                catch(e)
                {
                    console.log("Error message: " + e.message);
                }
                return false;
            }
        });
    }
    return false;
}

function reloadMessages(receiver)
{
    if($("#ajaxLoaderMessages").length)
    {
        $("#ajaxLoaderMessages").show();
    }
    var postData = {receiver: receiver, securityToken: $("#message-container").closest("input[name=security-token]").val()};
    var formURL = "/game/messages/reload";
    var method = "POST";
    var responseField = "#message-container";
    $.ajax({
   	    url : formURL,
   	    type: method,
   	    data : postData,
   	    success:function(data) 
   	    {
          try 
          {
              if($("#ajaxLoaderMessages").length)
              {
                  $("#ajaxLoaderMessages").hide();
              }
              $(responseField).html(data);
              $(responseField).stop().animate({
                  scrollTop: $(responseField)[0].scrollHeight
              }, 800);
              if($("input[name=receiver]").length)
              {
                  $("input[name=receiver]").val(receiver);
              }
          }
          catch(e)
          {
              console.log("Error message: " + e.message);
          }
   	    }
    });
    return false;
}

$(document).on("focusin", function(e) {
    e.stopImmediatePropagation();
});

function updateTime(){
    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
      "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];
    const monthNamesNL = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
      "Jul", "Aug", "Sep", "Okt", "Nov", "Dec"
    ];
    var currentTime = new Date();
    var day = currentTime.getDate();
    var month = monthNames[currentTime.getMonth()];
    var hours = currentTime.getHours();
    var hoursShow = hours;
    var minutes = currentTime.getMinutes();
    var seconds = currentTime.getSeconds();
    
    minutes = minutes < 10 ? "0" + minutes : minutes;
    seconds = seconds < 10 ? "0" + seconds : seconds;
    if($("html").hasAttr('lang') && $("html").attr('lang') == "en")
    {
        hoursShow = hoursShow > 12 ? hoursShow - 12 : hoursShow;
        hoursShow = hoursShow < 10 ? "0" + hoursShow : hoursShow;
        var am_pm = hours >= 12 ? " PM" : " AM";
        var t_str = month + " " + day + ", " + hoursShow + ":" + minutes + ":" + seconds + am_pm;
    }
    else
    {
        month = monthNamesNL[currentTime.getMonth()];
        hoursShow = hoursShow < 10 ? "0" + hoursShow : hoursShow;
        var t_str = day + " " + month + ", " + hoursShow + ":" + minutes + ":" + seconds;
    }
    $("#servertime").html(t_str);
}

function swipeMobileMenus()
{
    if($(window).width() <= 1074){
        $("html").on("dragstart", "a", function () {
            return false;
        });
        $("html").swipe({
            onMouse: true,
            onTouch: true,
            excludedElements: "button, input, select, textarea, .noSwipe, a, area, nav#messages > ul > li, ol.carousel-indicators > li, .modal.fade.show, .spoiler-title, .table-responsive table, label",
            swipeStatus:function(event, phase, direction, distance, duration, fingers)
            {
                if ((phase=="move" && direction =="up") || (phase=="move" && direction =="down") || (phase=="move" && direction =="in") || (phase=="move" && direction =="out"))
                    return false;
                    
                if (phase=="move" && direction =="right" && distance > 20) {
                    if($(".right-menu-swipe-area").hasClass("open"))
                    {
                        $(".right-menu-swipe-area").removeClass("open");
                        $("#right-menu").hide("fast");
                    }
                    else
                    {
                        $(".left-menu-swipe-area").addClass("open");
                        $("#left-menu").show("fast");
                        var body = $("html, body");
                        scrollTop();
                    }
                    return false;
                }
                if (phase=="move" && direction =="left" && distance > 20) {
                    if($(".left-menu-swipe-area").hasClass("open"))
                    {
                        $(".left-menu-swipe-area").removeClass("open");
                        $("#left-menu").hide("fast");
                    }
                    else
                    {
                        $(".right-menu-swipe-area").addClass("open");
                        $("#right-menu").show("fast");
                        var body = $("html, body");
                        scrollTop();
                    }
                    return false;
                }
            }
        });
    }
}

$(document).ready(function(){
    $("button[name=left-menu]").click(function (e) {
        $("#left-menu").toggle("fast");
        $(".left-menu-swipe-area").toggleClass("open");
        var body = $("html, body");
        scrollTop();
    });
    $("button[name=right-menu]").click(function (e) {
        $("#right-menu").toggle("fast");
        $(".right-menu-swipe-area").toggleClass("open");
        var body = $("html, body");
        scrollTop();
    });
    swipeMobileMenus();
    setInterval("updateTime()",1000);
});
