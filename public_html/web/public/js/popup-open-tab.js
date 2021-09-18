$.fn.hasAttr = function(name) {  
   return this.attr(name) !== undefined;
};

$(document).on("mousedown", ".ajaxTab", function (e) {e.preventDefault();e.stopPropagation(); });
$(document).on("mousedown", ".ajaxTabDisabled", function (e) {e.preventDefault();e.stopPropagation(); });
$(document).on("click", ".ajaxTabDisabled", function (e) {e.preventDefault();e.stopPropagation(); });
$(document).on("click", ".ajaxTab", function (e) {
    $(".ajaxTab").each(function( i ) {
        $(this).removeClass("ajaxTab");
        $(this).addClass("ajaxTabDisabled");
    });
    $("#openTab").empty();
    $("#modal").modal("hide");
    $(".modal-backdrop").remove();
    $("body").css('padding', "");
    var ajaxTab = $(this);
    var postData = {tab: $(this).attr("data-tab")};
    $.each( $(this).data(), function( key, value ) {
        postData[key] = value;
    });
    if($("#openTab").is(":empty")) {
        if($("#ajaxLoader").length)
        {
            $("#ajaxLoader").show();
        }
        $("#openTab").html("<span></span>");
        $.ajax(
        {
        	url : "/game/open-tab/" + $(this).attr("data-tab"),
        	type: "POST",
        	data : postData,
        	success:function(data) 
        	{
     	      if($("#ajaxLoader" ).length)
              {
                $("#ajaxLoader").hide();
              }        	   
        	  $("#openTab").html(data);
              if(ajaxTab.hasClass("active")) ajaxTab.removeClass("active");
              if(ajaxTab.attr("data-tab") == "messages") $("span[data-tab='messages']").remove();
              if(ajaxTab.attr("data-tab") == "notifications") $("span[data-tab='notifications']").remove();
              $(".ajaxTabDisabled").each(function( i ) {
                $(this).removeClass("ajaxTabDisabled");
                $(this).addClass("ajaxTab");
              });
        	}
        });
    }
    e.preventDefault();
    e.stopPropagation();
});
