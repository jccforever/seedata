define(['jquery', 'bootstrap', 'frontend', 'table', 'form'], function ($, undefined, Frontend, Table, Form) {
	var search = {
		tb:function(){
			$('#tbchaxun').on('click',function(){
                var start_page = parseInt($('input[name="page1"]').val());
                var end_page = parseInt($('input[name="page2"]').val());
                if(end_page<=start_page){
                    layer.tips('结束页码必须要大于起始页码','#notice')
                    return false;
                }
                if($('input[name="keys"]').val()==''){
                    layer.tips('关键词不能为空','#keywords');
                    return false;
                }
                if($('input[name="surl"]').val()==''){
                    layer.tips('宝贝链接不能为空','#url');
                    return false;
                }
                if($('input[name="surl"]').val().indexOf('taobao.com')===-1 && $('input[name="surl"]').val().indexOf('tmall.com')===-1)return alert('请输入合法的淘宝/天猫宝贝链接!');
                var post = {}; 
                post['search_mode']  = $('input:radio[name="smode"]:checked').val();
                post['device_type']  = $('input:radio[name="zhongduan"]:checked').val();
                post['sort']  = $('input:radio[name="desc"]:checked').val();
                post['page_start']  = $('input[name="page1"]').val();
                post['page_end']  = $('input[name="page2"]').val();
                post['keyword']  = $('input[name="keys"]').val();
                post['target']  = $('input[name="surl"]').val();
                var postjson = JSON.stringify(post);   
                $('#progress').show(0);
                $("#pro10").width('1%');
                var csswidth = 0;
                var timer = setInterval(changestyle,100);
                function changestyle() {
                    if(csswidth<=100){
                        csswidth++;
                        $("#pro10").width(csswidth+'%');
                        $("#pro10").text(csswidth+'%');
                    }
                }
                $.ajax({
                    data:{json:postjson},
                    dataType:'json',
                    type:'post',
                    url:'/business/get_tb',
                    success:function(res){
                        if(res.code==1){
                            clearInterval(timer);
                            $('#progress').hide(0);
                            var str = "<table class='table'><tbody><tr><td><img style='width:60px;height:60px' src='"+res.info.img+"'></td><td>"+res.info.title+"</td><td>宝贝坐标=("+res.info.page+"页,"+res.info.pos+"位)</td></tr>";
                            var out = "</tbody></table>";
                            $('#result').html(str+out);
                        }else{
                            clearInterval(timer);
                            $('#progress').hide(0);
                            $('#result').html(res.msg+res.leadTime);
                        }
                    }
                })
                return false;            
            })
		},
		jd:function(){
			$('#jdchaxun').on('click',function(){
                var start_page = $('input[name="page1"]').val();
                var end_page = $('input[name="page2"]').val();
                if(end_page<=start_page){
                    layer.tips('结束页码必须要大于起始页码','#notice')
                    return false;
                }
                if($('input[name="keys"]').val()==''){
                    layer.tips('关键词不能为空','#keywords');
                    return false;
                }
                if($('input[name="surl"]').val()==''){
                    layer.tips('宝贝链接不能为空','#url');
                    return false;
                }
                if($('input[name="surl"]').val().indexOf('jd.com')===-1) return alert('请输入合法的京东宝贝链接!');
                var post = {}; 
                post['search_mode']  = $('input:radio[name="smode"]:checked').val();
                post['device_type']  = $('input:radio[name="zhongduan"]:checked').val();
                post['sort']  = $('input:radio[name="desc"]:checked').val();
                post['page_start']  = $('input[name="page1"]').val();
                post['page_end']  = $('input[name="page2"]').val();
                post['keyword']  = $('input[name="keys"]').val();
                post['target']  = $('input[name="surl"]').val();
                var postjson = JSON.stringify(post);   
                $('#progress').show(0);
                $("#pro10").width('1%');
                var csswidth = 0;
                var timer = setInterval(changestyle,100);
                function changestyle() {
                    csswidth++;
                    $("#pro10").width(csswidth+'%');
                    $("#pro10").text(csswidth+'%');
                }
                $.ajax({
                    data:{json:postjson},
                    dataType:'json',
                    type:'post',
                    url:'/business/get_jd',
                    success:function(res){
                        console.log(res)
                        if(res.code==1){
                            clearInterval(timer);
                            $('#progress').hide(0);
                            var str = "<table class='table'><tbody><tr><td><img style='width:60px;height:60px' src='"+res.info.img+"'></td><td>"+res.info.title+"</td><td>宝贝坐标=("+res.info.page+"页,"+res.info.pos+"位)</td></tr>";
                            var out = "</tbody></table>";
                            $('#result').html(str+out);
                        }else{
                            clearInterval(timer);
                            $('#progress').hide(0);
                            $('#result').html(res.msg+res.leadTime);
                        }
                    }
                })
                return false;            
            })
		},
	}
	return{
		'search':search
	}
});