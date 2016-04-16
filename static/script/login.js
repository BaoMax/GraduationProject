$(document).ready(function(){

	$(".tips").attr("style","display:none");
	
	$('#UserName').focus(function(){
		$(".tips").eq(0).attr("style","margin:0;font-size: 0.5rem;text-indent: 1rem;color: green;display:block");
		$(".tips").eq(0).text("请输入9位数字学号");
	});
	$('#UserName').blur(function(){
		var reg = /^\d{9}$/;
		if(!reg.test($('#UserName').val())){
			$(".tips").eq(0).attr("style","margin:0;font-size: 0.5rem;text-indent: 1rem;color: red;display:block");
		}else{
			$(".tips").eq(0).attr("style","display:none");
		}
	});
	
	$('#Password').focus(function(){
		$(".tips").eq(1).attr("style","margin:0;font-size: 0.5rem;text-indent: 1rem;color: green;display:block");
		$(".tips").eq(1).text("请输入6-12位密码，由数字和字母组成");
	});
	$('#Password').blur(function(){
		var reg = /^\w{6,12}$/;
		if(!reg.test($('#Password').val())){
			$(".tips").eq(1).attr("style","margin:0;font-size: 0.5rem;text-indent: 1rem;color: red;display:block");
		}else{
			$(".tips").eq(1).attr("style","display:none");
		}
	});
	$('#login').click(function(){
		var reg1 = /^\d{9}$/;
		if(!reg1.test($('#UserName').val())){
			// alert("用户名不符合格式！")
			dialog("用户名不符合格式！")
			return ;
		}
		var reg2 = /^\w{6,12}$/;
		if(!reg2.test($('#Password').val())){
			dialog("密码不符合格式！")
			return ;
		}
		var url_link = window.location.href.slice(0,window.location.href.indexOf("?"));
		var flag = $("#remberBox").is(':checked')?true:false;
		$.ajax({
			type: "POST",
            url: url_link+'?c=login&a=login_test&rember='+flag,//提交的URL
            data: $('#form').serialize(), // 要提交的表单,必须使用name属性                    
            success: function (data) { 
            	console.log(data);
                  if(data == "1"){
                  	// $.cookie('UserName',$('#UserName').val(),{expire:3}); 
                  	// $.cookie('Password',$('#Password').val(),{expire:3});
                  	// if($("#remberBox").is(':checked')){
                  	// 	$.cookie('remberBox',true,{expire:3});
                  	// }else{
                  	// 	$.cookie('remberBox',false,{expire:3});
                  	// }
                  	var url = window.location.href;
                  	url = url.slice(0,url.indexOf("?"));
                  	window.location.href = url + "?c=main&a=selectCourse";
                  }else{
                  	dialog("用户名或密码错误！");
                  }
            },
            error: function (request) {
                 dialog("Connection error");
            }
		});
	});

	
	$('#Confirm').blur(function(){
		if($('#Password').val() != $('#Confirm').val()){
			$(".tips").eq(2).attr("style","margin:0;font-size: 0.5rem;text-indent: 1rem;color: red;display:block");
		}else{
			$(".tips").eq(2).attr("style","display:none");
		}
	});
	$('#Confirm').keyup(function(){
		if($('#Password').val() == $('#Confirm').val()){
			$(".tips").eq(2).attr("style","display:none");
		}
	});
	var url_link = window.location.href.slice(0,window.location.href.indexOf("?"));
	$('#register').click(function(){
		var reg1 = /^\d{9}$/;
		if(!reg1.test($('#UserName').val())){
			dialog("用户名不符合格式！")
			return ;
		}
		var reg2 = /^\w{6,12}$/;
		if(!reg2.test($('#Password').val())){
			dialog("密码不符合格式！")
			return ;
		}
		if($('#Password').val() != $('#Confirm').val()){
			dialog("密码不一致！");
			return ;
		}
		$.ajax({
			type: "POST",
            url: url_link+'?c=register&a=register_test',//提交的URL
            data: $('#form').serialize(), // 要提交的表单,必须使用name属性              
            success: function (data) {
                  if(data == "1"){
                  	var url = window.location.href;
                  	url = url.slice(0,url.indexOf("?"));
                  	window.location.href = url + "?c=login&a=login";
                  }else{
                  	dialog("用户名已存在！");
                  }
            },
            error: function (request) {
                 dialog("Connection error");
            }
		});
	});

});