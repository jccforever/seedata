define(['jquery', 'bootstrap', 'frontend', 'form', 'table'], function ($, undefined, Frontend, Form, Table) {
    var Controller = {
     //定义的方法
		tb: function () {
            $("body").bind("click",function(){
                var obj= $("input[id=nums]");
                var uprice=$("#uprice").val();//单价
                var sumtl=$('input:radio[name="goodstime"]:checked').val();//停留时间
                var days=$('input:radio[name="days"]:checked').val();//停留时间
                if (days == null) { var days=1;}
                var sum = 0;
                $.each(obj,function(e,element){
                    sum = Number(sum)+ Number(element.value);
                    //sum += parseInt(this.value);
                });
                if(sumtl == 6){
                    var uprices =  parseFloat(uprice)+0.03;//单个流量积
                }else if(sumtl == 8){
                    var uprices =  parseFloat(uprice)+0.05;//单个流量积
                }else if(sumtl == 10){
                    var uprices =  parseFloat(uprice)+0.07;//单个流量积
                }else{
                    var uprices =  parseFloat(uprice);//单个流量积
                }
                var ll_total = uprices*sum*days;
                $("#t_num").text(sum);//每天流量个数
                $("#t_key").text(obj.length);//关键词数
                $("#t_price").text(Math.round(uprices*1000)/1000);//单价
                $("#total_price").text(Math.round(ll_total*1000)/1000);//总价
                $("#t_days").text(Math.round(days*1000)/1000);//天数
            })
			Controller.api.bindevent();
        },
        retask: function () {
            $("body").bind("click",function(){
                var obj= $("input[id=nums]");
                var uprice=$("#uprice").val();//单价
                var sumtl=$('input:radio[name="goodstime"]:checked').val();//停留时间
                var days=$('input:radio[name="days"]:checked').val();//停留时间
                if (days == null) { var days=1;}
                var sum = 0;
                $.each(obj,function(e,element){
                    sum = Number(sum)+ Number(element.value);
                    //sum += parseInt(this.value);
                });
                if(sumtl == 6){
                    var uprices =  parseFloat(uprice)+0.03;//单个流量积
                }else if(sumtl == 8){
                    var uprices =  parseFloat(uprice)+0.05;//单个流量积
                }else if(sumtl == 10){
                    var uprices =  parseFloat(uprice)+0.07;//单个流量积
                }else{
                    var uprices =  parseFloat(uprice);//单个流量积
                }
                var ll_total = uprices*sum*days;
                $("#t_num").text(sum);//每天流量个数
                $("#t_key").text(obj.length);//关键词数
                $("#t_price").text(Math.round(uprices*1000)/1000);//单价
                $("#total_price").text(Math.round(ll_total*1000)/1000);//总价
                $("#t_days").text(Math.round(days*1000)/1000);//天数
            })
            Controller.api.bindevent();
        },
        jd: function () {
            $("body").bind("click",function(){
                var obj= $("input[id=nums]");
                var uprice=$("#uprice").val();//单价
                var sumtl=$('input:radio[name="goodstime"]:checked').val();//停留时间
                var days=$('input:radio[name="days"]:checked').val();//停留时间
                if (days == null) { var days=1;}
                var sum = 0;
                $.each(obj,function(e,element){
                    sum = Number(sum)+ Number(element.value);
                    //sum += parseInt(this.value);
                });
                if(sumtl == 6){
                    var uprices =  parseFloat(uprice)+0.03;//单个流量积
                }else if(sumtl == 8){
                    var uprices =  parseFloat(uprice)+0.05;//单个流量积
                }else if(sumtl == 10){
                    var uprices =  parseFloat(uprice)+0.07;//单个流量积
                }else{
                    var uprices =  parseFloat(uprice);//单个流量积
                }
                var ll_total = uprices*sum*days;
                $("#t_num").text(sum);//每天流量个数
                $("#t_key").text(obj.length);//关键词数
                $("#t_price").text(Math.round(uprices*1000)/1000);//单价
                $("#total_price").text(Math.round(ll_total*1000)/1000);//总价
                $("#t_days").text(Math.round(days*1000)/1000);//天数
            })
            Controller.api.bindevent();
        },
        pdd: function () {
            $("body").bind("click",function(){
                var obj= $("input[id=nums]");
                var uprice=$("#uprice").val();//单价
                var sumtl=$('input:radio[name="goodstime"]:checked').val();//停留时间
                var days=$('input:radio[name="days"]:checked').val();//停留时间
                if (days == null) { var days=1;}
                var sum = 0;
                $.each(obj,function(e,element){
                    sum = Number(sum)+ Number(element.value);
                    //sum += parseInt(this.value);
                });
                if(sumtl == 6){
                    var uprices =  parseFloat(uprice)+0.03;//单个流量积
                }else if(sumtl == 8){
                    var uprices =  parseFloat(uprice)+0.05;//单个流量积
                }else if(sumtl == 10){
                    var uprices =  parseFloat(uprice)+0.07;//单个流量积
                }else{
                    var uprices =  parseFloat(uprice);//单个流量积
                }
                var ll_total = uprices*sum*days;
                $("#t_num").text(sum);//每天流量个数
                $("#t_key").text(obj.length);//关键词数
                $("#t_price").text(Math.round(uprices*1000)/1000);//单价
                $("#total_price").text(Math.round(ll_total*1000)/1000);//总价
                $("#t_days").text(Math.round(days*1000)/1000);//天数
            })
            Controller.api.bindevent();
        },
        dy: function () {
            $("body").bind("click",function(){
                var obj= $("input[id=nums]");
                var uprice=$("#uprice").val();//单价
                var days=$('input:radio[name="days"]:checked').val();//停留时间
                if (days == null) { var days=1;}
                var sum = 0;
                $.each(obj,function(e,element){
                    sum = Number(sum)+ Number(element.value);
                    //sum += parseInt(this.value);
                });
                var ll_total = uprice*sum*days;
                $("#t_num").text(sum);//每天流量个数
                $("#t_price").text(Math.round(uprice*1000)/1000);//单价
                $("#total_price").text(Math.round(ll_total*1000)/1000);//总价
                $("#t_days").text(Math.round(days*1000)/1000);//天数
            })
            Controller.api.bindevent();
        },
        redy: function () {
            $("body").bind("click",function(){
                var obj= $("input[id=nums]");
                var uprice=$("#uprice").val();//单价
                var days=$('input:radio[name="days"]:checked').val();//停留时间
                if (days == null) { var days=1;}
                var sum = 0;
                $.each(obj,function(e,element){
                    sum = Number(sum)+ Number(element.value);
                    //sum += parseInt(this.value);
                });
                var ll_total = uprice*sum*days;
                $("#t_num").text(sum);//每天流量个数
                $("#t_price").text(Math.round(uprice*1000)/1000);//单价
                $("#total_price").text(Math.round(ll_total*1000)/1000);//总价
                $("#t_days").text(Math.round(days*1000)/1000);//天数
            })
            Controller.api.bindevent();
        },
        tblist: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'task/tblist',
                    table: 'task',
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
                        {field: 'task_name', title: '任务名'},
                        {field: 'task_key', title: '关键词'},
                        {field: 'taobao_id', title: '宝贝id'},
                        {field: 'task_num', title: '每日量'},
                        {field: 'total_num', title: '总量'},
                        {field: 'task_day_finish', title:'完成量',formatter:function(value,row,index){
                                    return "<span id='n"+row.id+"'>"+value+"</span>";
                            }},
                        {field: 'task_money', title: '总额'},
                        {field: 'task_day', title: '天数'},
                        {field: 'task_tpe', title: '类型',visible: false},
                        {field: 'task_status', title: '状态', searchList: {"0":'执行中',"1":'已结束'}, formatter: Table.api.formatter.normal},
                        {field: 'create_time', title: '提交时间', operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'buttons', title:'查询',formatter:function(value,row,index){
                                    return "<a href='javascript:void(0)' class='btn btn-xs btn-info call_off' id='"+row.id+"'><i class='fa fa-refresh'></i></a>"
                            }},
                        {field: 'buttons',title: '操作',table:table,events: Table.api.events.operate,buttons: [
                                {
                                    name:'edsa_ute',
                                    text:'重发',
                                    title:'重发',
                                    icon: 'fa fa-send',
                                    classname: 'btn btn-xs btn-success',
                                    url: 'task/retask',
                                },
                                {
                                    name:'edit_url',
                                    text:'结束',
                                    title:'结束',
                                    icon: 'fa fa-stop',
                                    classname: 'btn btn-xs btn-danger',
                                    url: 'task/end',
                                    hidden:function(row){
                                        if(row.task_status != '0'){
                                            return true;
                                        }
                                    }
                                }
                            ],formatter: Table.api.formatter.buttons}
                    ]
                ]
            });
            $(document).on('click','.call_off',function(){
                var tid = $(this).attr('id');
                $("#n"+tid).html("<img src='/assets/img/throbber.gif' />");
                $.ajax({
                    url:'task/getnum',
                    data:{tid:tid},
                    dataType:'json',
                    type:'post',
                    success:function(data){
                        $("#n"+tid).html(data.msg);
                    },
                    error:function(data){
                        alert(data);
                    }
                })
            })
            // 为表格绑定事件
            Table.api.bindevent(table);
            table.off('dbl-click-row.bs.table');
        },
        jdlist: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'task/jdlist',
                    table: 'task',
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
                        {field: 'task_name', title: '任务名'},
                        {field: 'task_key', title: '关键词'},
                        {field: 'taobao_id', title: '宝贝id'},
                        {field: 'task_num', title: '每日量'},
                        {field: 'total_num', title: '总量'},
                        {field: 'task_day_finish', title:'完成量',formatter:function(value,row,index){
                                return "<span id='n"+row.id+"'>"+value+"</span>";
                            }},
                        {field: 'task_money', title: '总额'},
                        {field: 'task_day', title: '天数'},
                        {field: 'task_tpe', title: '类型',visible: false},
                        {field: 'task_status', title: '状态', searchList: {"0":'执行中',"1":'已结束'}, formatter: Table.api.formatter.normal},
                        {field: 'create_time', title: '提交时间', operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'buttons', title:'查询',formatter:function(value,row,index){
                            return "<a href='javascript:void(0)' class='btn btn-xs btn-info call_off' id='"+row.id+"'><i class='fa fa-refresh'></i></a>"
                        }},
                        {field: 'buttons',title: '操作',table:table,events: Table.api.events.operate,buttons: [
                                {
                                    name:'edsa_ute',
                                    text:'重发',
                                    title:'重发',
                                    icon: 'fa fa-send',
                                    classname: 'btn btn-xs btn-success',
                                    url: 'task/retask',
                                },
                                {
                                    name:'edit_url',
                                    text:'结束',
                                    title:'结束',
                                    icon: 'fa fa-stop',
                                    classname: 'btn btn-xs btn-danger',
                                    url: 'task/end',
                                    hidden:function(row){
                                        if(row.task_status != '0'){
                                            return true;
                                        }
                                    }
                                }
                            ],formatter: Table.api.formatter.buttons}
                    ]
                ]
            });
            $(document).on('click','.call_off',function(){
                var tid = $(this).attr('id');
                $("#n"+tid).html("<img src='/assets/img/throbber.gif' />");
                $.ajax({
                    url:'task/getnum',
                    data:{tid:tid},
                    dataType:'json',
                    type:'post',
                    success:function(data){
                        $("#n"+tid).html(data.msg);
                    },
                    error:function(data){
                        alert(data);
                    }
                })
            })
            // 为表格绑定事件
            Table.api.bindevent(table);
            table.off('dbl-click-row.bs.table');
        },
        pddlist: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'task/pddlist',
                    table: 'task',
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
                        {field: 'task_name', title: '任务名'},
                        {field: 'task_key', title: '关键词'},
                        {field: 'taobao_id', title: '宝贝id'},
                        {field: 'task_num', title: '每日量'},
                        {field: 'total_num', title: '总量'},
                        {field: 'task_day_finish', title:'完成量',formatter:function(value,row,index){
                                return "<span id='n"+row.id+"'>"+value+"</span>";
                            }},
                        {field: 'task_money', title: '总额'},
                        {field: 'task_day', title: '天数'},
                        {field: 'task_tpe', title: '类型',visible: false},
                        {field: 'task_status', title: '状态', searchList: {"0":'执行中',"1":'已结束'}, formatter: Table.api.formatter.normal},
                        {field: 'create_time', title: '提交时间', operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'buttons', title:'查询',formatter:function(value,row,index){
                                return "<a href='javascript:void(0)' class='btn btn-xs btn-info call_off' id='"+row.id+"'><i class='fa fa-refresh'></i></a>"
                            }},
                        {field: 'buttons',title: '操作',table:table,events: Table.api.events.operate,buttons: [
                                {
                                    name:'edsa_ute',
                                    text:'重发',
                                    title:'重发',
                                    icon: 'fa fa-send',
                                    classname: 'btn btn-xs btn-success',
                                    url: 'task/retask',
                                },
                                {
                                    name:'edit_url',
                                    text:'结束',
                                    title:'结束',
                                    icon: 'fa fa-stop',
                                    classname: 'btn btn-xs btn-danger',
                                    url: 'task/end',
                                    hidden:function(row){
                                        if(row.task_status != '0'){
                                            return true;
                                        }
                                    }
                                }
                            ],formatter: Table.api.formatter.buttons}
                    ]
                ]
            });
            $(document).on('click','.call_off',function(){
                var tid = $(this).attr('id');
                $("#n"+tid).html("<img src='/assets/img/throbber.gif' />");
                $.ajax({
                    url:'task/getnum',
                    data:{tid:tid},
                    dataType:'json',
                    type:'post',
                    success:function(data){
                        $("#n"+tid).html(data.msg);
                    },
                    error:function(data){
                        alert(data);
                    }
                })
            })
            // 为表格绑定事件
            Table.api.bindevent(table);
            table.off('dbl-click-row.bs.table');
        },
        dylist: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'task/dylist',
                    table: 'task',
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
                        {field: 'task_name', title: '任务名'},
                        {field: 'taobao_id', title: '宝贝id'},
                        {field: 'task_num', title: '每日量'},
                        {field: 'total_num', title: '总量'},
                        {field: 'task_day_finish', title:'完成量',formatter:function(value,row,index){
                                return "<span id='n"+row.id+"'>"+value+"</span>";
                            }},
                        {field: 'task_money', title: '总额'},
                        {field: 'task_day', title: '天数'},
                        {field: 'task_tpe', title: '类型',visible: false},
                        {field: 'task_status', title: '状态', searchList: {"0":'执行中',"1":'已结束'}, formatter: Table.api.formatter.normal},
                        {field: 'create_time', title: '提交时间', operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'buttons', title:'查询',formatter:function(value,row,index){
                                return "<a href='javascript:void(0)' class='btn btn-xs btn-info call_off' id='"+row.id+"'><i class='fa fa-refresh'></i></a>"
                            }},
                        {field: 'buttons',title: '操作',table:table,events: Table.api.events.operate,buttons: [
                                {
                                    name:'edsa_ute',
                                    text:'重发',
                                    title:'重发',
                                    icon: 'fa fa-send',
                                    classname: 'btn btn-xs btn-success',
                                    url: 'task/redy',
                                },
                                {
                                    name:'edit_url',
                                    text:'结束',
                                    title:'结束',
                                    icon: 'fa fa-stop',
                                    classname: 'btn btn-xs btn-danger',
                                    url: 'task/end',
                                    hidden:function(row){
                                        if(row.task_status != '0'){
                                            return true;
                                        }
                                    }
                                }
                            ],formatter: Table.api.formatter.buttons}

                    ]
                ]
            });
            $(document).on('click','.call_off',function(){
                var tid = $(this).attr('id');
                $("#n"+tid).html("<img src='/assets/img/throbber.gif' />");
                $.ajax({
                    url:'task/getnum',
                    data:{tid:tid},
                    dataType:'json',
                    type:'post',
                    success:function(data){
                        $("#n"+tid).html(data.msg);
                    },
                    error:function(data){
                        alert(data);
                    }
                })
            })
            // 为表格绑定事件
            Table.api.bindevent(table);
            table.off('dbl-click-row.bs.table');
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