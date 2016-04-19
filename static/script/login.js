$(document).ready(function(){
	$("#form").validator({
		onValid:function(validity){
			$(validity.field).closest('.am-form-group').find('.am-alert').hide();
		},
		onInValid:function(validity){
			var $field = $(validity.field);
			var $group = $field.closest('.am-form-group');
			var $alert = $group.find('.am-alert');

			var msg = $field.data('validationMessage') || this.getValidationMessage(validity);

			if(!$alert.length){
				$alert = $('<div class="am-alert am-alert-danger"></div>').hide();
				$alert.appendTo($group);
			}
			$alert.html(msg).show();
		}
	});
	$('#login').click(function(){
		var url_link = window.location.href.slice(0,window.location.href.indexOf("?"));
		var flag = $("#remberBox").is(':checked')?true:false;
		$.ajax({
			type: "POST",
            url: url_link+'?c=login&a=login_test&rember='+flag,//提交的URL
            data: $('#form').serialize(), // 要提交的表单,必须使用name属性                    
            success: function (data) { 
            	console.log(data);
                  if(data == "1"){
                  	var url = window.location.href;
                  	url = url.slice(0,url.indexOf("?"));
                  	window.location.href = url + "?c=main&a=mainPage";
                  }else{
                  	dialog("用户名或密码错误！");
                  }
            },
            error: function (request) {
                 dialog("Connection error");
            }
		});
	});

	var url_link = window.location.href.slice(0,window.location.href.indexOf("?"));
	$('#register').click(function(){
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