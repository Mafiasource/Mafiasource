$.ajaxPrefilter(function(options){
    options.async = true;
});

var ajaxBusy = false;
$(document).ajaxStart( function(){ 
    ajaxBusy = true; 
}).ajaxStop( function(){
    ajaxBusy = false;
});

$(".closeCookieMessage").click(function(e){
    $.ajax({
        url : "/accept-cookies",
        type: "POST",
        data : {type: "accept"}
    });
});

$(document).ready(function(){
    $("body").prepend('<div id="mobile-menu-wrapper"><input type="checkbox" id="mobile-menu" name="menu" class="menu-checkbox"><nav class="menu"><label class="menu-toggle" for="mobile-menu"><span>Toggle</span></label>' + $("nav#menu").html() + '</nav></div>');
    $("html").height($(document).height());
});

if("serviceWorker" in navigator) {
  window.addEventListener("load", function() {
    navigator.serviceWorker
      .register("/sw.js")
      .then(res => console.log("service worker registered"))
      .catch(err => console.log("service worker not registered", err));
  });
}
