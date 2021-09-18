$(document).ready(function(){
  $(".submenu > a").click(function(e){
    e.preventDefault();
    var $li = $(this).parent("li");
    var $ul = $(this).next("ul");

    if($li.hasClass("open")) {
      $ul.slideUp(350);
      $li.removeClass("open");
    } else {
      $(".nav > li > ul").slideUp(350);
      $(".nav > li").removeClass("open");
      $ul.slideDown(350);
      $li.addClass("open");
    }
  });
  $("form.ajaxForm").submit(function(e){
    var postData = $(this).serializeArray();
	var formURL = $(this).attr("action");
	var method = $(this).attr("method");
    var responseField = $(this).attr("data-response");
	$.ajax(
	{
		url : formURL,
		type: ""+method+"",
		data : postData,
		success:function(data) 
		{
		  $(responseField).html(data);
		}
	});
    e.preventDefault();
  });
});
