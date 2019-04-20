define(['jquery', 'bootstrap', 'frontend', 'form', 'table'], function ($, undefined, Frontend, Form, Table) {
    var validatoroptions = {
        invalid: function (form, errors) {
            $.each(errors, function (i, j) {
                Layer.msg(j);
            });
        }
    };
    var Controller = {
        login: function () {
            //本地验证未通过时提示
            $("#login-form").data("validator-options", validatoroptions);

            $(document).on("change", "input[name=type]", function () {
                var type = $(this).val();
                $("div.form-group[data-type]").addClass("hide");
                $("div.form-group[data-type='" + type + "']").removeClass("hide");
                $('#resetpwd-form').validator("setField", {
                    captcha: "required;length(4);integer[+];remote(" + $(this).data("check-url") + ", event=resetpwd, " + type + ":#" + type + ")",
                });
                $(".btn-captcha").data("url", $(this).data("send-url")).data("type", type);
            });

            //为表单绑定事件
            Form.api.bindevent($("#login-form"), function (data, ret) {
                setTimeout(function () {
                    location.href = ret.url ? ret.url : "/";
                }, 1000);
            });

            Form.api.bindevent($("#resetpwd-form"), function (data) {
                Layer.closeAll();
            });

            $(document).on("click", ".btn-forgot", function () {
                var id = "resetpwdtpl";
                var content = Template(id, {});
                Layer.open({
                    type: 1,
                    title: __('Reset password'),
                    area: ["450px", "355px"],
                    content: content,
                    success: function (layero) {
                        Form.api.bindevent($("#resetpwd-form", layero), function (data) {
                            Layer.closeAll();
                        });
                    }
                });
            });
        },
        register: function () {
            //本地验证未通过时提示
            $("#register-form").data("validator-options", validatoroptions);

            //为表单绑定事件
            Form.api.bindevent($("#register-form"), function (data, ret) {
                setTimeout(function () {
                    location.href = ret.url ? ret.url : "/";
                }, 1000);
            });
        },
        changepwd: function () {
            //本地验证未通过时提示
            $("#changepwd-form").data("validator-options", validatoroptions);

            //为表单绑定事件
            Form.api.bindevent($("#changepwd-form"), function (data, ret) {
                setTimeout(function () {
                    location.href = ret.url ? ret.url : "/";
                }, 1000);
            });
        },
        profile: function () {
            // 给上传按钮添加上传成功事件
            $("#plupload-avatar").data("upload-success", function (data) {
                var url = Fast.api.cdnurl(data.url);
                $(".profile-user-img").prop("src", url);
                Toastr.success(__('Upload successful'));
            });
            Form.api.bindevent($("#profile-form"));
            $(document).on("click", ".btn-change", function () {
                var that = this;
                var id = $(this).data("type") + "tpl";
                var content = Template(id, {});
                Layer.open({
                    type: 1,
                    title: "修改",
                    area: ["400px", "250px"],
                    content: content,
                    success: function (layero) {
                        var form = $("form", layero);
                        Form.api.bindevent(form, function (data) {
                            location.reload();
                            Layer.closeAll();
                        });
                    }
                });
            });
        },
	   money_log: function () {
             // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'user/money_log',
                    table: 'user_money_log',
                }
            });
		   var table = $("#table");
		   // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
				showExport: false,
                columns: [
                    [
                        {field:'id','title':'#'},
                        {field: 'money', title: '充值金额'},
                        {field: 'memo', title: __('Memo')},
                        {field: 'createtime', title:'充值时间', operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime}
                         
                    ]
                ]
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        index:function(){
            window.bvip = function(ids){
                $.ajax({
                    data:{ids:ids},
                    dataType:'json',
                    type:'post',
                    url:'/user/pay',//线上支付
                    success:function(res){
                        if(res.code==1){
                            location.href = res.url;
                        }
                    }
                })
            }
        },
        invite_list:function(){
             // 初始化表格参数配置
             Table.api.init({
                extend: {
                    index_url: 'user/invite_list',
                    table: 'user',
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
                search:false,
                columns: [
                    [
                        {field:'id','title':'#'},
                        {field: 'username', title: '用户名',formatter:function(value,row,index){
                            var name = row.username;
                            var str = name.substr(0,3);
                            var res = str+'****';
                            return res;  
                        }},
                        {field: 'memo', title: __('Memo'),formatter:function(value,row,index){
                            if(row.father_level==4){
                                return "推广用户获得2天VIP会员";
                            }else{
                                return "推广用户获得5天高级会员";
                            }
                        }},
                        {field: 'createtime', title:'注册时间', operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime}   
                    ]
                ]
            });
        }
    };
    return Controller;
});