function dialog(text){
	var str = '<div class="am-modal am-modal-alert" tabindex="-1" id="my-alert">'
  		+'<div class="am-modal-dialog">'
   		 	+'<div class="am-modal-hd"></div>'
   		 	+'<div class="am-modal-bd">'+text+'</div>'
   		 	+'<div class="am-modal-footer">'
     		 	+'<span class="am-modal-btn">确定</span>'
   		 	+'</div>'
  		+'</div>'
	+'</div>';
	$("body").append(str);
}
function goBack(){
	window.history.go(-1);
  location.reload();
}
function more(type){
  var index = window.location.href.indexOf("?");
  var url = window.location.href.slice(0,index);
  window.location.href = url+"?c=main&a=course&type="+type;
}
function course(course_id){
  var index = window.location.href.indexOf("?");
  var url = window.location.href.slice(0,index);
  window.location.href = url+"?c=main&a=catalog&course_id="+course_id;
}
function userCenter(){
	var url = window.location.href;
    url = url.slice(0,url.indexOf("?"));
    var student_id = $.cookie("UserName"); 
    var course_name = $.cookie("value_"+student_id);
    window.location.href = url + "?c=main&a=userCenter&student_id="+student_id+"&course_name="+course_name;
}
// function catalog(){
// 	  var url = window.location.href;
//     url = url.slice(0,url.indexOf("?"));
//     var courseId = $.cookie("key_"+$.cookie("UserName"));
//     var courseName = $.cookie("value_"+$.cookie("UserName"));
//     window.location.href = url + "?c=main&a=catalog&course_id="+courseId+"&course_name="+courseName;
// }
function getUrlParam(name){
	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
	var r = window.location.search.substr(1).match(reg);
	if (r!=null) return unescape(r[2]); return null;
}