define(['jquery', 'bootstrap', 'frontend', 'form', 'table','echarts','search'], function ($, undefined, Frontend, Form, Table,Echarts,Search) {
    var Controller = {
    	taobao:function(){
            Search.search.tb();
    	},
    	jingdong:function(){
            Search.search.jd();
            console.log('hello')
    	},
    	monitor:function(){
    		 // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'business/monitor',
                    table: 'links',
                }
            });
		  	var table = $("#table");
			 // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
				showToggle: false,
				showColumns: false,
                showExport: false,
                commonSearch: false,
                columns: [
                    [
                        {field: 'id', title: '#'},
                        {field: 'terrace', title:'平台'},
                        {field: 'remark', title: '备注',visible: false},
                        {field: 'link_id', title: '宝贝ID/SKU',formatter:function(value,row,index){
                        	if(row.terrace=="淘宝"){
                        		return "<a href='https://item.taobao.com/item.htm?id="+value+"' target='_blank'>"+value+"</a>"
                        	}else if(row.terrace=="京东"){
                        		return "<a href='https://item.jd.com/"+value+".html' target='_blank'>"+value+"</a>"
                        	}else if(row.terrace=="天猫"){
                                return "<a href='https://detail.tmall.com/item.htm?id="+value+"' target='_blank'>"+value+"</a>"
                            }
                        }},
                        {field: 'shop_name', title: '店铺', operate:false},
                        {field: 'keywords_num', title: '关键字数量'},
                        {field: 'update_time', title:'更新时间' , operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field:'operate','title':__('Operate'),formatter:function(value,row,index){
                            var search = '<a href="/Business/search?id='+row.id+'&link_id='+row.link_id+'&terrace='+row.terrace+'"  data-toggle="tooltip" data-original-title="查看详情"><i class="fa fa-search" aria-hidden="true"></i></a> ';
                            var chart = '<a href="/business/monitor_echarts?id='+row.id+'&keywords_id=00&link_id='+row.link_id+'&terrace='+row.terrace+'" data-toggle="tooltip"  data-original-title="查看趋势"><i class="fa fa-line-chart" aria-hidden="true"></i> ';
                            var del = '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="删除监控" onclick="del_link('+row.link_id+')"><i class="fa fa-trash-o" aria-hidden="true"></i> ';
                            return search+chart+del;
                        }}   
                    ]
                ]
            });
            //添加要监控的宝贝
            window.addjkbtn = function(){
            	if($("#tagkey").val()==""||$("#tagurl").val()=="")return alert("关键词和商品链接不能为空！");
            	var tagurl = $('#tagurl').val();//获取宝贝链接
            	//正则匹配宝贝链接是否合法
            	if(tagurl.indexOf('taobao.com')===-1 && tagurl.indexOf('jd.com')===-1 && tagurl.indexOf('tmall.com')===-1)return alert('请输入合法的淘宝/天猫/京东宝贝链接!');
            	//获取数据 组装成json对象
            	var formdata={"links":$("#tagurl").val(),"keywords":$("#tagkey").val(),"tip_caption":$("#tagtip").val()};
            	formdata = JSON.stringify(formdata);
            	$('#addjkbtn').prop('disabled',true);
            	$('#addjkbtn').html('提交中......'); 
            	$.ajax({
            		type:"post",
            		dataType:'json',
            		data:{formdata:formdata},
            		url:"/Business/save_monitor",
            		success:function(res){
            			if(res.code==1){
            				layer.msg(res.msg);
            				window.location.href = res.url;
            			}else{
            				layer.msg(res.msg);
            				$('#addjkbtn').prop('disabled',false);
            				$('#addjkbtn').html('再次提交');
            			}
            		}
            	});
            };
            //删除宝贝
            window.del_link = function(ids){
                $.ajax({
                    data:{link_id:ids},
                    dataType:'json',
                    type:'post',
                    url:'/business/del_link',
                    success:function(res){
                        if(res.code==1){
                            layer.msg(res.msg);
                            window.location.reload();
                        }else{
                            layer.msg(res.msg);
                        }
                    }
                });
            }
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        monitor_echarts:function(){
            function options(names,datas,dates,title){
                var option = {
                    title: {
                        text: title
                    },
                    tooltip: {
                        trigger: 'axis'
                    },
                    legend: {
                        data:names
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    toolbox: {
                        feature: {
                            saveAsImage: {}
                        }
                    },
                    xAxis: {
                        type: 'category',
                        boundaryGap: false,
                        data: dates
                    },
                    yAxis: {
                        inverse: true,//当它为true时y从小到大
                        type: 'value'
                    },
                    series:datas
                };
                return option;
            }
            function getUrlParam(name)
            {
                var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
                var r = window.location.search.substr(1).match(reg);
                if(r!=null)return  decodeURI(r[2]); return null;
            }
            $('input[name="days"]').on('click',function(){
                var period = $(this).val();
                get_echarts(period);
            })
            $(document).ready(function(){
                var period = $('input[name="days"]:checked').val();
                get_echarts(period);
            })
            function get_echarts(period){
                var ids = getUrlParam('id');
                var keywords_id = getUrlParam('keywords_id');
                var link_id = getUrlParam('link_id');
                $('#xqsku').html(link_id);
                var terrace = getUrlParam('terrace');
                if(terrace=='天猫' || terrace=='淘宝'){
                    $('#ptimg').attr('src','/assets/img/tb.png');
                }else if(terrace=='京东'){
                    $('#ptimg').attr('src','/assets/img/jd.png');
                }
                $.ajax({
                    data:{id:ids,keywords_id:keywords_id,period:period},
                    dataType:'json',
                    type:'post',
                    url:'/business/get_monitor_echarts',
                    success:function(res){
                        var json = res.info;
                        var names = new Array();
                        var date = new Array();
                        var datas = new Array();
                        var j = 0;
                        var dates = new Array();
                        var title = '关键词排名折线图堆叠';
                        for(var i in json){
                            names.push(json[i].name);
                            datas[j] = {name:json[i].name,data:json[i].data,type:'line'};
                            j++;
                            dates = json[i].time;
                        }
                        if(j==1){
                            title = '单个关键词排名折线图'
                        }
                        var myChart = Echarts.init(document.getElementById('tpl-echarts-A'));
                        var option = options(names,datas,dates,title);
                        myChart.setOption(option);
                    }
                })
            }
        },
    	search:function(){
            function getUrlParam(name)
            {
                var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
                var r = window.location.search.substr(1).match(reg);
                if(r!=null)return  decodeURI(r[2]); return null;
            };
            $(document).ready(function(){
                var ids = getUrlParam('id');
                var link_id = getUrlParam('link_id');
                var terrace = getUrlParam('terrace');
                $.ajax({
                    data:{ids:ids},
                    type:'post',
                    dataType:'json',
                    url:'/business/get_monitor_detail',
                    success:function(res){
                      var info = res.data;
                      $('#xqprice').html(info.goods_price);
                      $('#xqsale').html(info.sale_count);
                      $('#xqping').html(info.remark_count);
                      $('#xqimg').attr('src',info.goods_img);
                      $('#xqdian').html(info.shop_name);
                      $('#xqsku').html(info.goods_id);
                      $('#xqtitle').html(info.goods_title);
                      $('#allzs').attr('href','/business/monitor_echarts?keywords_id=00&id='+ids+'link_id='+link_id+'&terrace='+terrace+'')
                      if(info.terrace=='京东'){
                        $('#ptimg').attr('src','/assets/img/jd.png');
                      }else if(info.terrace=='淘宝' || info.terrace=='天猫'){
                        $('#ptimg').attr('src','/assets/img/tb.png');
                      }
                    }
                })
            });
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'business/search?link_id='+getUrlParam('link_id'),
                    table: 'link_keywords',
                }
            });
            var table = $("#table");
             // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                showToggle: false,
                showColumns: false,
                showExport: false,
                commonSearch: false,
                columns: [
                    [
                        {field: 'id', title: '序号',operate: false},
                        {field: 'keywords', title:'关键词',operate: false},
                        {field: 'link_id', title: '宝贝ID/SKU',visible:false },
                        {field:'mobile',title:'手机端排名',formatter:function(value,row,index){
                            if(value !=''){
                                var str = row.mobile;
                                obj = JSON.parse(str)
                                return '第'+obj.page+'页 第'+obj.pos+'个';
                            }
                        },operate: false},
                        {field:'mobile_update_time',title:'手机端更新时间',operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime,operate: false},
                        {field:'pc',title:'电脑端排名',operate: false},
                        {field:'pc_update_time',title:'电脑端更新时间',operate: false},
                        {field:'operate','title':__('Operate'),formatter:function(value,row,index){
                            var echart =' <a href="/business/monitor_echarts?keywords_id='+row.id+'&link_id='+row.link_id+'&terrace='+row.terrace+'" class="am-icon-bar-chart"></a> ';
                            var str = ' - ';
                            var del = '<a href="javascript:void(0)" class="am-icon-trash-o" onclick="deljkbtn('+row.id+',\''+row.keywords+'\','+row.link_id+')"></a>';
                            return echart+str+del;
                        }}  
                    ]
                ]
            });
            //保存关键词
            window.addkeybtn = function(){
				var ids = $("#link_id").val();//宝贝id
				var str=$('#tagkeys').val();//获取关键词
                var id = getUrlParam('id');//商品主键
                if(str=='')return alert('关键词不能为空')
				$('#addkeybtn').prop('disabled',true);
				$('#addkeybtn').html('提交中.....');
				var formdata={"link_id":ids,"keywords":str,'id':id};
				$.ajax({
					data:{formdata:JSON.stringify(formdata)},
					type:'POST',
					dataType:'json',
					url:'/Business/save_keywords',
					success:function(res){
						if(res.code==1){
							layer.msg(res.msg);
							window.location.href = res.url;
						}else{
							alert(res.msg);
							$('#addkeybtn').prop('disabled',false);
							$('#addkeybtn').html('再次提交');
						}
					}
				});
            }
            //删除关键词
            window.deljkbtn = function(ids,is_one,link_id){
                $.ajax({
                    dataType:'json',
                    type:'post',
                    data:{ids:ids,is_one:is_one,link_id:link_id},
                    url:'/business/del_link',
                    success:function(res){
                        if(res.code==1){
                            if(res.is_false==0){
                                layer.msg(res.msg);
                                window.location.href = res.url;
                            }else{
                                layer.msg(res.msg);
                                window.location.reload();
                            }
                        }else{
                            layer.msg(res.msg);
                        }
                    }
                })
            }
            // 为表格绑定事件
            Table.api.bindevent(table);
    	},
    	contend:function(){
    		// 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'business/contend',
                    table: 'competitor',
                }
            });
		  	var table = $("#table");
			// 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
				showToggle: false,
				showColumns: false,
				showExport: false,
                columns: [
                    [
                        {field: 'id', title: '序号'},
                        {field:'terrace',title:'平台'},
                        {field:'link_id',title:'宝贝ID/SKU',formatter:function(value,row,index){
                        	if(row.terrace=="淘宝"){
                        		return "<a href='https://item.taobao.com/item.htm?id="+value+"' target='_blank'>"+value+"</a>"
                        	}else if(row.terrace=="京东"){
                        		return "<a href='https://item.jd.com/"+value+".html' target='_blank'>"+value+"</a>"
                        	}else if(row.terrace=="天猫"){
                                return "<a href='https://detail.tmall.com/item.htm?id="+value+"' target='_blank'>"+value+"</a>"
                            }
                        }},
                        {field: 'shop_name', title: '店铺'},
                        {field: 'last_update_time', title: '更新时间',operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field:'operate','title':__('Operate'),formatter:function(value,row,index){
                            var check = '<a href="/business/contend_detail?id='+row.id+'" class="btn btn-xs btn-success"  data-toggle="tooltip" title="" data-table-id="table" data-field-index="15" data-row-index="0" data-button-index="1" data-original-title="查看"><i class="fa fa-search" aria-hidden="true"></i></a>  ';
                            var del = '<a href="javascript:void(0)" onclick=del_competitor("'+row.id+'") class="btn btn-xs btn-danger" data-toggle="tooltip" title="" data-table-id="table" data-field-index="15" data-row-index="0" data-button-index="1" data-original-title="删除"><i class="fa fa-trash-o" aria-hidden="true"></i></a> ';
                            return check+del;
                        }} 
                    ]
                ]
            });
            //添加竞品
            window.addjpbtn = function(){
            	var jpurl = $('#jpurl').val();
            	var jptip = $('#jptip').val();
            	if(jpurl=="") return alert('商品链接不能为空!');
            	$('#addjpbtn').prop('disabled',true);
            	$('#addjpbtn').html('提交中....');
            	var formdata={jpurl:jpurl,jptip:jptip};
            	$.ajax({
            		data:{formdata:JSON.stringify(formdata)},
            		url:'/Business/save_contend',
            		dataType:"json",
            		type:'POST',
            		success:function(res){
            			if(res.code==1){
            				layer.msg(res.msg);
            				window.location.href = res.url;
            			}else{
            				layer.msg(res.msg);
            				$('#addjpbtn').prop('disabled',false);
            				$('#addjpbtn').html('再次提交');
            			}
            		}
            	});
            };
            //删除竞品
            window.del_competitor = function(id){
            	layer.confirm('确定要删除吗',function(){
            		$.ajax({
            			data:{ids:id},
            			dataType:'json',
            			type:'post',
            			url:'/business/del_competitor',
            			success:function(res){
            				if(res.code==1){
            					layer.msg(res.msg);
            					window.location.href = res.url;
            				}else{
            					layer.msg(res.msg);
            				}
            			}
            		});
            	});
            }
            // 为表格绑定事件
            Table.api.bindevent(table);
    	},
        contend_detail:function(){
            function getUrlParam(name)
            {
                var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
                var r = window.location.search.substr(1).match(reg);
                if(r!=null)return  unescape(r[2]); return null;
            }
            function options (names,nums,title,notice){
                var option = {
                    title:{
                        text:title
                    },
                    tooltip:{
                        show:true
                    },
                    legend:{
                        data:[notice]
                    },
                    xAxis:[{
                        data:names
                    }],
                    yAxis:[{
                        type : 'value'
                    }],
                    series:[{
                        'name':notice,
                        'type':'line',
                        'data':nums
                    }]
                }; 
                return option;
            }
            $(document).ready(function(){
                var ids = getUrlParam('id');
                $.ajax({
                    data:{for_id:ids},
                    dataType:'json',
                    type:'post',
                    url:'/business/get_competitor',
                    success:function(res){
                        var formdata = res.data;
                        console.log(formdata);
                        var output = "<table class='am-table am-table-striped am-table-bordered am-table-hover' id='tablist'><thead><tr><th>标题</th><th>持续</th></tr></thead><tbody>";
                        for(var i in formdata){
                            if(!formdata[i].end_title){
                                var titles = "<font color='#0e90d2'>现:</font>"+formdata[i].start_title+" <font color='#0e90d2'>使用中<br>原:</font>"+formdata[i].start_title+" <font color='#0e90d2'>监控:"+formdata[i].start_time+"</font>";
                            }else{
                                var titles = "<font color='#0e90d2'>现:</font>"+formdata[i].end_title+" <font color='#0e90d2'>修改:"+formdata[i].end_time+"<br>原:</font>"+formdata[i].start_title+" <font color='#0e90d2'>监控:"+formdata[i].start_time+"</font>";
                            }
                            output+="<tr><td>"+titles+"</td><td>维持<font color='#0e90d2'>"+formdata[i].count_days+"</font>天</td></tr>";
                        }
                        $('#tab1').html(output+"</tbody></table>");
                    }
                });
            })
            window.getPic = function(){
                var ids = getUrlParam('id');
                $.ajax({
                    data:{for_id:ids},
                    dataType:'json',
                    type:'post',
                    url:'/business/get_pic',
                    success:function(res){
                        var keydata = res.data;
                        var output = "<table class='am-table am-table-striped am-table-bordered am-table-hover' id='piclist'><thead><tr><th>现图</th><th>原图</th><th>监控周期</th><th>持续</th></tr></thead><tbody>";
                        for(var i in keydata){
                          if(keydata[i].end_time){
                                var zdata = "<font color='#0e90d2'>"+keydata[i].start_time+"到"+keydata[i].end_time+"</font>";
                            }else{
                                var zdata = "<font color='#0e90d2'>"+keydata[i].start_time+"到 现在</font>";
                            }
                            output+="<tr><td><img width='120' src='"+keydata[i].start_title+"'></td><td><img width='120' src='"+keydata[i].start_title+"'></td><td>"+zdata+"</td><td>维持<font color='#0e90d2'>"+keydata[i].count_days+"</font>天</td></tr>";
                        }
                        $('#tab5').html(output+"</tbody></table>");
                    }
                })
            }
            window.jpzs = function(fieldname){
                var formdata = {"id":getUrlParam('id'),"fieldname":fieldname};
                var requestJson = JSON.stringify(formdata);
                $.ajax({
                    data:{jsonData:requestJson},
                    dataType:'json',
                    type:'post',
                    url:'/business/get_echarts',
                    success:function(res){
                        if(res.code==1){
                            var json = res.json;
                            if(fieldname=='goods_price'){
                                var myChart = Echarts.init(document.getElementById('pricecharts'));
                                var names = new Array();
                                var nums = new Array();
                                for(var i in json){
                                    names.push(json[i].add_time);
                                    nums.push(json[i].goods_price);
                                }
                                var option = options(names,nums,'价格折线图','价格变化趋势')
                                myChart.setOption(option);
                            }else if(fieldname=='goods_sales_count'){
                                var myChart = Echarts.init(document.getElementById('salesecharts'));
                                var names = new Array();
                                var nums = new Array();
                                for(var i in json){
                                    names.push(json[i].add_time);
                                    nums.push(json[i].sale_count);
                                }
                                var option = options(names,nums,'销量折线图','销量变化趋势')
                                myChart.setOption(option);
                            }else if(fieldname=='goods_comment'){
                                var myChart = Echarts.init(document.getElementById('commentecharts'));
                                var names = new Array();
                                var nums = new Array();
                                for(var i in json){
                                    names.push(json[i].add_time);
                                    nums.push(json[i].remark_count);
                                }
                                var option = options(names,nums,'评价折线图','评价数变化趋势')
                                myChart.setOption(option);
                            }
                        }
                    }
                })
            }
        }
    };
    return Controller;
});