define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'task/index',
                    add_url: 'task/add',
                    edit_url: 'task/edit',
                    del_url: 'task/del',
                    multi_url: 'task/multi',
                    table: 'task',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'user.username', title: __('User.username'),formatter: Table.api.formatter.search},
                        {field: 'task_id', title: __('Task_id'),visible: false},
                        {field: 'taobao_url', title: __('Taobao_url'), formatter: Table.api.formatter.url,visible: false},
                        {field: 'taobao_id', title: __('Taobao_id')},
                        {field: 'task_key', title: __('Task_key')},
                        {field: 'hourCounts', title: __('Hourcounts'),visible: false},
                        {field: 'task_num', title: __('Task_num')},
                        {field: 'task_day_finish', title:'完成量',formatter:function(value,row,index){
                                return "<span id='n"+row.id+"'>"+value+"</span>";
                            }},
                        {field: 'total_num', title: '总数'},
                        {field: 'task_day', title: '天数'},
                        {field: 'task_price', title: '单价', operate:'BETWEEN'},
                        {field: 'task_money', title: '总价', operate:'BETWEEN'},
                        {field: 'task_c', title: __('Task_c'),visible: false},
                        {field: 'task_fs', title: __('Task_fs'),visible: false},
                        {field: 'goodstime', title: __('Goodstime'),visible: false},
                        {field: 'task_status', title: __('Task_status'), searchList: {"0":__('Task_status 0'),"1":__('Task_status 1')}, formatter: Table.api.formatter.status},
                        {field: 'task_tpe', title: __('Task_tpe'), searchList: {"1":__('Task_tpe 1'),"2":__('Task_tpe 2'),"3":__('Task_tpe 3'),"4":__('Task_tpe 4'),"5":__('Task_tpe 5'),"6":__('Task_tpe 6'),"7":__('Task_tpe 7'),"8":__('Task_tpe 8'),"9":__('Task_tpe 9'),"10":__('Task_tpe 10'),"11":__('Task_tpe 11'),"12":__('Task_tpe 12'),"13":__('Task_tpe 13'),"14":__('Task_tpe 14'),"15":__('Task_tpe 15'),"16":__('Task_tpe 16')}, formatter: Table.api.formatter.normal},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'buttons', title:'查询',formatter:function(value,row,index){
                                return "<a href='javascript:void(0)' class='btn btn-xs btn-info call_off' id='"+row.id+"'><i class='fa fa-refresh'></i></a>"
                            }},
                        {field: 'buttons',title: '结束',table:table,events: Table.api.events.operate,buttons: [
                                {
                                    name:'endit_url',
                                    text:'结束',
                                    title:'结束',
                                    icon: 'fa fa-stop',
                                    classname: 'btn btn-xs btn-danger',
                                    url: 'task/adminend',
                                    hidden:function(row){
                                        if(row.task_status != '0'){
                                            return true;
                                        }
                                    }
                                }
                            ],formatter: Table.api.formatter.buttons},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });
            $(document).on('click','.call_off',function(){
                var tid = $(this).attr('id');
                $("#n"+tid).html("<img src='/assets/img/throbber.gif' />");
                $.ajax({
                    url:'task/admingetnum',
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
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});