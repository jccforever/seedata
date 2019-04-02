define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'express/index',
                    add_url: 'express/add',
                    edit_url: 'express/edit',
                    del_url: 'express/del',
                    multi_url: 'express/multi',
                    table: 'express',
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
                        {field: 'express_no', title: __('Express_no')},
                        {field: 'out_order_no', title: __('Out_order_no'),visible: false},
                        {field: 'expressid', title: __('Expressid'), searchList: {"1":__('Expressid 1'),"2":__('Expressid 2'),"3":'圆通拼多多'}, formatter: Table.api.formatter.normal},
                        {field: 'price', title: __('Price'), operate:'BETWEEN'},
                        {field: 'weight', title: __('Weight'), operate:'BETWEEN'},
                        {field: 'goods', title: __('Goods'), operate:false},
                        {field: 'addressee', title: __('Addressee')},
                        {field: 'a_mphone', title: __('A_mphone'),formatter: Table.api.formatter.search},
                        {field: 'all_address', title: __('All_address')},
                        {field: 'f_shen', title: __('F_shen'),visible: false, operate:false},
                        {field: 'f_shi', title: __('F_shi'),visible: false, operate:false},
                        {field: 'f_qu', title: __('F_qu'),visible: false, operate:false},
						{field: 'dianpu', title: '旺旺/店铺',formatter: Controller.api.formatter.dianpu},
						{field: 'addressid', title:'发件号码',visible: false},
                        {field: 'f_di', title: __('F_di'), operate:false},
						{field: 'from', title: __('From'), searchList: {"0":__('From 0'),"1":__('From 1')}, formatter: Table.api.formatter.normal},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            

            // 为表格绑定事件
            Table.api.bindevent(table);
			table.off('dbl-click-row.bs.table');
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            formatter: {
                dianpu: function (value, row, index) {
                    //这里手动构造URL
                    url = "/admin/address?" + this.field + "=" + value;
                    //方式一,直接返回class带有addtabsit的链接,这可以方便自定义显示内容
                    return '<a href="' + url + '" class="label label-success addtabsit" title="' + __("Search %s", value) + '">' + __('Search %s', value) + '</a>';
                    //方式二,直接调用Table.api.formatter.addtabs
                    //return Table.api.formatter.addtabs(value, row, index, url);
                }
            }
        }
    };
    return Controller;
});