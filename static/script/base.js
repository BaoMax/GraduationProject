function dialog(text){
	var height = $('html').height();
	var str = "<div id='shade'></div>";
	$("body").append(str);
	$("#shade").height(height);
    $("#shade").append("<div id='dialogBox'><p>"+text+"</p><a><button id='sure'>确定</button></a></div>");
	$("#sure").click(function(){
		$("#shade").remove();
	});
}
function goBack(){
	window.history.go(-1);
}
function userCenter(){
	var url = window.location.href;
    url = url.slice(0,url.indexOf("?"));
    var student_id = $.cookie("UserName"); 
    var course_name = $.cookie("value_"+student_id);
    window.location.href = url + "?c=main&a=userCenter&student_id="+student_id+"&course_name="+course_name;
}
function catalog(){
	var url = window.location.href;
    url = url.slice(0,url.indexOf("?"));
    var courseId = $.cookie("key_"+$.cookie("UserName"));
    var courseName = $.cookie("value_"+$.cookie("UserName"));
    window.location.href = url + "?c=main&a=catalog&course_id="+courseId+"&course_name="+courseName;
}
function getUrlParam(name){
	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
	var r = window.location.search.substr(1).match(reg);
	if (r!=null) return unescape(r[2]); return null;
}