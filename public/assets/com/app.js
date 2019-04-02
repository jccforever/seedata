$(function() {
    // 读取body data-type 判断是哪个页面然后执行相应页面方法，方法在下面。
    
   
    //     // 判断用户是否已有自己选择的模板风格
    //    if(storageLoad('SelcetColor')){
    //      $('body').attr('class',storageLoad('SelcetColor').Color)
    //    }else{
    //        storageSave(saveSelectColor);
    //        $('body').attr('class','theme-black')
    //    }

    autoLeftNav();
    $(window).resize(function() {
        autoLeftNav();
        //console.log($(window).width())
    });

    //    if(storageLoad('SelcetColor')){

    //     }else{
    //       storageSave(saveSelectColor);
    //     }
})
// 侧边菜单开关

function autoLeftNav() {



    $('.tpl-header-switch-button').on('click', function() {
        if ($('.left-sidebar').is('.active')) {
            if ($(window).width() > 1024) {
                $('.tpl-content-wrapper').removeClass('active');
            }
            $('.left-sidebar').removeClass('active');
        } else {

            $('.left-sidebar').addClass('active');
            if ($(window).width() > 1024) {
                $('.tpl-content-wrapper').addClass('active');
            }
        }
    })

    if ($(window).width() < 1024) {
        $('.left-sidebar').addClass('active');
    } else {
        $('.left-sidebar').removeClass('active');
    }
}

// 侧边菜单
$('.sidebar-nav-sub-title').on('click', function() {
    $(this).siblings('.sidebar-nav-sub').slideToggle(80)
        .end()
        .find('.sidebar-nav-sub-ico').toggleClass('sidebar-nav-sub-ico-rotate');
})

  // 查询方式获取选中的值
  $(function() {
    var $radios = $('[name="smode"]');
	var $paixu = $('[name="zhongduan"]');
	  
    $radios.on('change',function() {
	var $iszd =  $radios.filter(':checked').val();
	  if($iszd == 0){
		 $('#ptls').text('商品链接'); 
		 $('#jdptls').text('商品链接'); 
		 $('#tips').attr('placeholder','输入宝贝链接或者宝贝ID');
		 $('#jdtips').attr('placeholder','输入商品链接或者商品SKU'); 
	  }else{
		 $('#ptls').text('旺旺号'); 
		 $('#jdptls').text('店铺名称'); 
		 $('#tips').attr('placeholder','输入店铺的旺旺号');
		 $('#jdtips').attr('placeholder','输入店铺的名字');
	  }	
      //console.log('单选框当前选中的是：', $iszd);
    });
	  
	$paixu.on('change',function() {
	var $pxid =  $paixu.filter(':checked').val();
	   if($pxid == 1){
		   $("#isrq").removeClass("am-disabled");
	  }else{
		    $("#isrq").addClass("am-disabled");
	  }	
     // console.log('单选框当前选中的是：', $pxid);
    });  
  });

document.onkeyup = function(e){      //onkeyup是javascript的一个事件、当按下某个键弹起 var _key;                                                 //的时触发  
    if (e == null) { // ie  
        _key = event.keyCode;  
    } else { // firefox              //获取你按下键的keyCode  
        _key = e.which;          //每个键的keyCode是不一样的  
    }  
      
    if(_key == 13){   //判断keyCode是否是13，也就是回车键(回车的keyCode是13)  
     //if (validator(document.loginform)){ //这个因该是调用了一个验证函数  
         document.getElementById('btn-login').click()    //验证成功触发一个Id为btnLogin的  
        //}                                                                        //按钮的click事件，达到提交表单的目的  
    }  
} 

//登录
$("#btn-login").click(function(){
	if($("#loginMobile").val()==""||$("#loginCode").val()==""||$("#loginPassword").val()=="")return alert("登录信息填写不正确！");
        var formdata={"vcode":$("#loginCode").val(),"mobile":$("#loginMobile").val(),"password":$("#loginPassword").val()};
		var requestJson ={
			request_name:"anonymous.ecsch_login",
			json_data:JSON.stringify(formdata)
		};
		$.ajax({
			type:'POST',
			url:'/wx_ajax.jsp',
			data:requestJson,
			success:function(param){
			param = JSON.parse(param);
				if(param.status==1){
				   //  location.reload(true);
					if(getUrlParam('c')==''||getUrlParam('c')==null){
						$(location).attr('href', '/cha/user.html');
					}else{
						$(location).attr('href', getUrlParam('c'));
					}
					
				}else {
					alert(param.status_text);
				}
			},
			error:function(param){
				alert('系统错误');
			}
		});

});

//判断登录
var isloginJson = {
			request_name:"anonymous.ecsch_islogin",
	        json_data:''
	        	};
 
$.ajax({
			type:"POST",
			url:'/wx_ajax.jsp',
			data:isloginJson,
			success:function(data,status){
				 //alert(JSON.stringify(data));
				if(data.status == 1){
					var output="<li class='am-text-sm tpl-header-navbar-welcome'><a href='/cha/user.html'>欢迎你, <span>"+data.json_data.mobile+"</span></a></li><li class='am-dropdown tpl-dropdown'><a href='/cha/user.html'><i class='am-icon-user'></i></a></li><li class='am-text-sm'><a onclick='logout()' href='javascript:;'><span class='am-icon-sign-out'></span> 退出 </a></li>";
						 //alert(json_data.mobile);
					$("#islogin").html(output).fadeIn(300);
				}else{
					var output="请您登录";
		           //$("#islogin").html(output).fadeIn(300);  
				}
			},
			error:function(){
				var output="请登录";
		       // $("#islogin").html(output).fadeIn(300);
			},
			dataType:"json"
		});
	 //手机号验证
    function checkPhone(phone){
        if(!(/^1[345789]\d{9}$/.test(phone))){
            return false;
        }else{
            return true;
        }
    }
//获取验证码
function getcode(){
 if(!$("#regmobile").val()){
				alert('请输入手机号!');
		       $("#regmobile").focus();
				return false;
			}
 if(checkPhone($("#regmobile").val())){    
        }else{
                alert('请输入正确的手机号');
				$("#regmobile").focus();
				return false;         
        }
	if(!$("#regpassword").val()){
				alert('请输入6位以上密码!');
		       $("#regpassword").focus();
				return false;
			}
	if(!$("#regqq").val()){
				alert('QQ号不能为空!');
		       $("#regqq").focus();
				return false;
			}
	if(!$("#regimgcode").val()){
				alert('请输入图形验证码!');
		       $("#regimgcode").focus();
				return false;
			}
	
	    var formdata={"mobile":$("#regmobile").val(),"vcode":$("#regimgcode").val()};
		var requestJson ={
			request_name:"anonymous.ecsch_vcode",
			json_data:JSON.stringify(formdata)
		};
	$.ajax({
			type:'POST',
			url:'/wx_ajax.jsp',
			data:requestJson,
			success:function(param){
			param = JSON.parse(param);
				if(param.status==1){
				   //  location.reload(true);
					 $('#regmobile').attr('readonly',true); 
			         $('#getcodes').attr('disabled',true); 
			        $('#getcodes').text("验证已发送,注意查收");
				}else {
					alert(param.status_text);
				}
			},
			error:function(param){
				alert('系统错误');
			}
		});
}

//注册
$("#btn-reg").click(function(){
        var regdata ={
			"scode":$("#regscode").val(),
			"mobile":$("#regmobile").val(),
			"invite_code":$("#reginvite").val(),
			"rpassword":$("#regpassword").val(),
			"qq":$("#regqq").val()
		};
		var requestJson ={
			request_name:"anonymous.ecsch_user_reg",
			json_data:JSON.stringify(regdata)
		};
		$.ajax({
			type:'POST',
			url:'/wx_ajax.jsp',
			data:requestJson,
			success:function(param){
			param = JSON.parse(param);
				if(param.status==1){
				   //  location.reload(true);
					$(location).attr('href', '/cha/user.html');
				}else {
					alert(param.status_text);
				}
			},
			error:function(param){
				alert('系统错误');
			}
		});

});
 

//微信登录
$("#wxlogin").click(function(){
	var wximgJson = {
			request_name:"anonymous.ecsch_qr_code",
	        json_data:''
	        	};
	$.ajax({
			type:"POST",
			url:'/wx_ajax.jsp',
			data:wximgJson,
			success:function(data,status){
				 //alert(JSON.stringify(data));
				if(data.status == 1){
						 //alert(json_data.mobile);
					$("#wximg").attr("src",data.json_data.img_url);	
					var myVar = setInterval(function(){ 
					  var isloginwx = {
						request_name:"anonymous.ecsch_islogin",
						json_data:''
							};
					$.ajax({
								type:"POST",
								url:'/wx_ajax.jsp',
								data:isloginwx,
								success:function(data,status){
									 //alert(JSON.stringify(data));
									if(data.status == 1){
										$("#wximgerr").html('登录成功,跳转到会员中心'); 
										clearInterval(myVar);
										if(getUrlParam('c')==''||getUrlParam('c')==null){
												$(location).attr('href', '/cha/user.html');
											}else{
												$(location).attr('href', getUrlParam('c'));
											}
															}else{
										var output="请您登录";
									}
								},
								error:function(){
									var output="请登录";
								},
								dataType:"json"
							});	
					}, 1000);
					
				}else{
					$("#wximgerr").html('获取二维码失败，请刷新重新操作！').fadeIn(300);
				}
			},
			error:function(){
				$("#wximgerr").html('系统错误，请联系客服！').fadeIn(300);
			},
			dataType:"json"
		});
			
});

//得到url参数
function getUrlParam(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
            var r = window.location.search.substr(1).match(reg);  //匹配目标参数
            if (r != null) return unescape(r[2]); return null; //返回参数值
        }

//登录注销
function logout(){
		if(!confirm("真的要注销吗？"))return;
  		var formdata ={};		
			var requestJson ={
				request_name:"anonymous.ecsch_logout",
				json_data:''
			};
			
			 $.ajax({
				type:'POST',
				url:'/wx_ajax.jsp',
				data:requestJson,
				success:function(param){
					param = JSON.parse(param);
					if(param.status==1){
						//alert("注销成功");
						localStorage.setItem("logined",false);
						localStorage.setItem("loginedMobile","");
						location.reload(true);
						$(location).attr('href', '/');
					}else{
						alert('退出失败'+JSON.stringify(param));
					}
				},
				error:function(param){
					alert('系统错误'+JSON.stringify(param));
				}
			});
		
	};