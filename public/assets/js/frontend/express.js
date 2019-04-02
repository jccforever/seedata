define(['jquery', 'bootstrap', 'frontend', 'form', 'table'], function ($, undefined, Frontend, Form, Table) {
 
    var Controller = {
     //定义的方法
		 exlist: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'express/exlist',
                    add_url: 'express/add',
                    table: 'express',
                }
            });
		  var table = $("#table");
			 // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
				showToggle: false,
				exportDataType: 'selected',
                columns: [
                    [
                        {field: 'id', title: __('Id')},
                        {field: 'dianpu', title: __('Dianpu')},
                        {field: 'express_no', title: __('Express_no')},
                        {field: 'out_order_no', title: __('Out_order_no'),visible: false},
                        {field: 'expressid', title: __('Expressid'), searchList: {"1":'圆通',"2":'中通',"3":'圆通拼多多'}, formatter: Table.api.formatter.normal},
                        {field: 'price', title: __('Price'), operate:false},
                        {field: 'weight', title: __('Weight'), operate:false},
                        {field: 'goods', title: __('Goods'), operate:false},
                        {field: 'addressee', title: __('Addressee')},
                        {field: 'a_mphone', title: __('A_mphone'),formatter: Table.api.formatter.search},
                        {field: 'all_address', title: __('All_address'), operate:false},
                        {field: 'from', title: __('From'), searchList: {"0":'普通',"1":'批量'}, formatter: Table.api.formatter.normal,visible: false},
						{field: 'tableid', title: '导入编号',visible: false},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                         
                    ]
                ]
            });

            

            // 为表格绑定事件
            Table.api.bindevent(table);
			table.off('dbl-click-row.bs.table');
		 },
		 adds: function () {
			 $(".btn-editone").data("area", ['800px','470px']);
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'express/adds',
                    add_url: '/express/newadds',
					edit_url: '/express/editdds',
                    table: 'address',
                }
            });
		  var table = $("#table");
			 // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
				showToggle: false,
				exportDataType: 'selected',
				search:false,
				showToggle: false,
				showColumns: false,
				showExport: false,
				commonSearch: false,
                columns: [
                    [
                        {field: 'dianpu', title: '旺旺'},
                        {field: 'fajianren', title: '发件人'},
                        {field: 'shouji', title: '手机'},
                        {field: 'a_province', title: '省'},
                        {field: 'city', title: '市'},
                        {field: 'area', title: '区'},
                        {field: 'address', title: '地址'},
						{field: 'ismr', title: '是否默认', searchList: {"0":'否',"1":'是'}, formatter: Table.api.formatter.normal},
                        {field: 'statusswitch', title: '状态', searchList: {"0":'冻结',"1":'正常'}, formatter: Table.api.formatter.status},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                         {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,formatter: Table.api.formatter.operate}
                    ]
                ]
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
			table.off('dbl-click-row.bs.table');
		 },
		ulist: function () {
			 $(".btn-editone").data("area", ['800px','470px']);
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'express/ulist',
                    table: 'upinfo',
                }
            });
		  var table = $("#table");
			 // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
				showToggle: false,
				exportDataType: 'selected',
				search:false,
				showToggle: false,
				showColumns: false,
				showExport: false,
				commonSearch: false,
                columns: [
                    [
                        {field: 'id', title: __('Id')},
                         {field: 'expressid', title: __('Expressid'), searchList: {"1":'圆通',"2":'中通',"3":'圆通拼多多'}, formatter: Table.api.formatter.normal},
                        {field: 'exnum', title: '导入总数'},
                        {field: 'oknum', title: '导入成功数'},
                        {field: 'vars', title: '说明'},
                        {field: 'tableid', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
						{field: 'operate', title: __('Operate'), table: table,events: Table.api.events.operate,width:'160px',
                        buttons: [
                                {name: 'buttons', text: '明细', title: '明细', icon: '', classname: 'btn btn-xs btn-dialog',url:'/express/exlist?tableid={tableid}'},
                                {name: 'buttons', text: '导出', title: '导出', icon: '', classname: 'btn btn-xs btn-derive',url:'/express/export?tableid={tableid}'}
                            ],formatter: Table.api.formatter.operate}   
                    ]
                ]
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
			table.off('dbl-click-row.bs.table');
		 },
		buy: function () {
			$("body").bind("click",function(){
				var expressid=$('input:radio[name="expressid"]:checked').val();//
				$.ajax({
                          data:{expressid:expressid},
                          dataType:'json',
                          url:'/Express/get_kuaidi',
                          type:'post',
                          success:function(res){
                              $("#lp").text(res);
                             }
                        })
                var sum = 0;
                $.each($("#weight1"),function(){ sum += Number(this.value);});
                var ll_total = sum+0.3;
                $('#weight2').attr('value',ll_total);
            });
			
			// Fast.api.open('/express/uploads', 'title', '')
			$(document).on("click", ".btn-dialog", function () {
			  var adds =$("#addstext").val();
			  var addts1 =adds.replace(/，/g,','); 
			  var addtext =addts1.replace(/^\s+|\s+$/g,''); 
              var addtextarr= new Array(); 
			  var adddan=new Array();
			      addtextarr=addtext.split("\n");
				for(i=0;i<addtextarr.length;i++){
					if(addtextarr[i]!=''){
						adddan=addtextarr[i].split(",");
						if(adddan.length!=3){alert("第"+(i+1)+"个收货地址格式有错误，请仔细检查！"); return; }
						if(adddan[1].length!=11){alert("第"+(i+1)+"个地址的手机号码格式不对，请仔细检查！"); return;}
					}else{
					alert("第"+(i+1)+"个地址不能为空,请删除空数据");
					return;
					}
				}
                 alert('验证通过'+addtextarr.length+'个地址，请提交');
				
                $("#addstext").val(addtext); 
				$('.btn-embossed').attr('disabled',false);
            });
			Controller.api.bindevent();
		},
		uploads: function () {
			$("body").bind("click",function(){
				var expressid=$('input:radio[name="expressid"]:checked').val();//停留时间
				$.ajax({
                          data:{expressid:expressid},
                          dataType:'json',
                          url:'/Express/get_kuaidi',
                          type:'post',
                          success:function(res){
                              $("#lp").text(res);
                             }
                        })
                var sum = 0;
                $.each($("#weight1"),function(){ sum += Number(this.value);});
                var ll_total = sum+0.3;
                $('#weight2').attr('value',ll_total);
			});	
			
			// 给上传按钮添加上传成功事件
            $("#plupload-xls").data("upload-success", function (data) {
                var url = Fast.api.cdnurl(data.url);
			//	console.log('数组：', data);
				$(".plupload").text('已选择：'+url);
               // Toastr.success(__('Upload successful'));
            });
			 //为表单绑定事件
			Controller.api.bindevent();
        },
		newadds: function () {
             Controller.api.bindevent();
        },
		editdds: function () {
             Controller.api.bindevent();
        },
		api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"), function (data, ret) {
                    setTimeout(function () {
                        location.href = ret.url;
                    }, 1000);
                },function(data,ret){
                    setTimeout(function () {
                        location.href = ret.url;
                    }, 2000);
                });
            }
        }
    };
    return Controller;
});