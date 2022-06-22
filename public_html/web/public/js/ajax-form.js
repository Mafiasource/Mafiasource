$(document).on("submit", "form.ajaxForm", function(e){
    if(ajaxBusy == false)
    {
        if($("#ajaxLoader").length)
        {
            $("#ajaxLoader").show();
        }
        var postData = $(this).serializeArray();
        var formURL = $(this).attr("action");
        var method = $(this).attr("method");
        var responseField = $(this).attr("data-response");
        var btn = $("input[type=submit][clicked=true]").attr("name");
        var btnVal = $("input[type=submit][clicked=true]").val();
        postData.push({name: btn, value: btnVal});
        $.ajax(
        {
        	url : formURL,
        	type: method,
        	data : postData,
            async: false,
        	success:function(data) 
        	{
     	      if($("#ajaxLoader" ).length)
              {
                $("#ajaxLoader").hide();
              }
              if($(".writeAndFlush" ).length && (data.includes("alert-success") || data.includes("<script")) && !data.includes("alert-danger"))
              {
                $(".writeAndFlush").val("");
              }
        	  $(responseField).html(data);
        	}
        });
    }
    e.preventDefault();
});
$(document).on("click", "form.ajaxForm input[type=submit]", function(){
    $("input[type=submit]").each(function(){
        $(this).removeAttr("clicked");
    });
    $(this).attr("clicked", "true");
});
$(document).on("submit", "form.ajaxFormUpload", function(e){
    if(ajaxBusy == false)
    {
        if($("#ajaxLoader").length)
        {
            $("#ajaxLoader").show();
        }
        var postData = new FormData($(this)[0]);
        var formURL = $(this).attr("action");
        var method = $(this).attr("method");
        var responseField = $(this).attr("data-response");
        $.ajax(
        {
        	url : formURL,
        	type: method,
            processData: false,
            contentType: false,
            cache: false,
        	data : postData,
            enctype: "multipart/form-data",
        	success:function(data)
        	{
     	      if($("#ajaxLoader" ).length)
              {
                $("#ajaxLoader").hide();
              }
              if($(".writeAndFlush" ).length && (data.includes("alert-success") || data.includes("<script")) && !data.includes("alert-danger"))
              {
                $(".writeAndFlush").val("");
              }
        	  $(responseField).html(data);
        	}
        });
    }
    e.preventDefault();
});
