$.fn.hasAttr = function(name) {  
   return this.attr(name) !== undefined;
};

$.ajaxPrefilter(function(options) {
    options.async = true;
});

function tableOptionsAjaxSubmission(id, table, actionUri, responseNode, securityToken, hideString, showString)
{
    $(".sortable > tbody").sortable("destroy");
    hideString = typeof hideString !== "undefined" ? hideString : "";
    showString = typeof showString !== "undefined" ? showString : "";
    $.ajax({
        type: "POST",
        url: location.protocol + "//" + location.hostname + actionUri,
        data: {id, table, securityToken, hideString, showString},
        success:function(data) 
  		{
  		    $(responseNode).html(data);
            sortTableRecordsRows();
  		}
    }); 
}

var securityToken = $("#security-token").val();

var fixHelper = function(e, ui) {
	ui.children().each(function() {
		$(this).width($(this).width());
	});
	return ui;
};

function sortTableRecordsRows()
{
    $("table, thead, th, tr, td:not(.opties)").sortable({
      disabled: true
    });
    $(".sortable > tbody").disableSelection().sortable({
        delay: 50,
        axis: "y",
        cursor: "move",
        handle: "span.moveRow",
        cancel: "a,td:not(.opties)",
        placeholder: "ui-state-highlight",
        containment: "parent",
        tolerance: "pointer",
    	helper: fixHelper,
        scroll: true,
        scrollSensitivity: 80,
        scrollSpeed: 3,
        update: function() {
            var table = $(".table.table-bordered.sortable").attr("data-table");
            var startFrom = parseInt($(".table.table-bordered.sortable").attr("start"));
            var stringDiv = "";
            $(".sortable > tbody").children().each(function(i) {
                var li = $(this);
                stringDiv += " " + li.attr("id") + "=" + (i + startFrom) + "&";
            });
            stringDiv += "table="+table + "&securityToken=" + securityToken;
            $.ajax({
                type: "POST",
                url: location.protocol + "//" + location.hostname + "/admin/sort",
                data: stringDiv,
                success:function(data) 
          		{
          		    $("#orderChangeConfirm").html(data);
          		}
            }); 
        }
    });
}
$(document).on("mousedown", "a.delete:not(.deleteSure)", function (e) {
    var id = $(this).attr("data-id");
    var table = $("table.table.table-bordered.sortable").attr("data-table");
    var hideString = $(this).attr("data-hide-elements");
    var showString = $(this).attr("data-show-elements");
    var actionUri = "/admin/delete-confirm";
    tableOptionsAjaxSubmission(id, table, actionUri, "#deleteConfirm", securityToken, hideString, showString);
    e.preventDefault();
    e.stopPropagation();
});
$(document).on("click", "button.deleteSure:not(.delete)", function (e) {
    var id = $(this).attr("data-id");
    var table = $(this).attr("data-table");
    var actionUri = "/admin/delete";
    tableOptionsAjaxSubmission(id, table, actionUri, "#deleteSureConfirm", securityToken);
    e.preventDefault();
    e.stopPropagation();
});
$(document).on("click", "button.saveRecord", function (e) {
    var postData = new FormData($(this).closest("form#editForm")[0]);
	var formURL = $(this).closest("form#editForm").attr("action");
	$.ajax(
	{
        cache: false,
        contentType: false,
        processData: false,
		url : formURL,
		type: "POST", 
		data : postData, 
		success:function(data) 
		{
		  $("#editSaveConfirm").html(data); 
		}
	});
    e.preventDefault();
    e.stopPropagation(); 
});
$(document).on("mousedown", "a.editOrNew", function (e) {
    if($(this).hasAttr("data-id")) { var id = $(this).attr("data-id"); } else { var id = ""; }
    var table = $("table.table.table-bordered.sortable").attr("data-table");
    if($(this).hasAttr("data-id")) { var actionUri = "/admin/edit/" + id; } else { var actionUri = "/admin/new"; }
    tableOptionsAjaxSubmission(id, table, actionUri, "#editOrNewConfirm", securityToken);
    e.preventDefault();
    e.stopPropagation();
    
});
$(document).on("mousedown", "a.activate", function (e) {
    var id = $(this).attr("data-id");
    var table = $("table.table.table-bordered.sortable").attr("data-table");
    var actionUri = "/admin/activate";
    tableOptionsAjaxSubmission(id, table, actionUri, "#activateDeactivateConfirm", securityToken);
    e.preventDefault();
    e.stopPropagation();
});
$(document).on("mousedown", "a.deactivate", function (e) {
    var id = $(this).attr("data-id");
    var table = $("table.table.table-bordered.sortable").attr("data-table");
    var actionUri = "/admin/deactivate";
    tableOptionsAjaxSubmission(id, table, actionUri, "#activateDeactivateConfirm", securityToken);
    e.preventDefault();
    e.stopPropagation();
});
$(document).on("mousedown", "input.positionchange", function (e) {
    $("input[name='search']").val("");
    $("select[name='search-by']").val(0);
    $("#search-form-records").submit();
    e.preventDefault();
    e.stopPropagation();
});

$("#search-form-records").submit(function (e) {
    $(".sortable > tbody").sortable('destroy');
    $('#ajaxLoader').show();
    $("#searchFormResponse").html("");
    var postData = $(this).serializeArray();
	var formURL = $(this).attr("action");
	var method = $(this).attr("method");
	$.ajax(
	{
		url : formURL,
		type: method,
		data : postData,
		success:function(data) 
		{
		  $("#searchFormResponse").html(data);
          $('#ajaxLoader').hide();
          sortTableRecordsRows();
		}
	});
    e.preventDefault();
});

$(document).ready(function(e){
    sortTableRecordsRows();
});
