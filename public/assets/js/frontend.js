define(['fast', 'template', 'moment'], function (Fast, Template, Moment) {
    var Frontend = {
        api: Fast.api,
        init: function () {
            var si = {};
            //发送验证码
            $(document).on("click", ".btn-captcha", function (e) {
                var type = $(this).data("type") ? $(this).data("type") : 'mobile';
                var element = $(this).data("input-id") ? $("#" + $(this).data("input-id")) : $("input[name='" + type + "']", $(this).closest("form"));
                var text = type === 'email' ? '邮箱' : '手机号码';
                if (element.val() === "") {
                    Layer.msg(text + "不能为空！");
                    element.focus();
                    return false;
                } else if (type === 'mobile' && !element.val().match(/^1[3-9]\d{9}$/)) {
                    Layer.msg("请输入正确的" + text + "！");
                    element.focus();
                    return false;
                } else if (type === 'email' && !element.val().match(/^[\w\+\-]+(\.[\w\+\-]+)*@[a-z\d\-]+(\.[a-z\d\-]+)*\.([a-z]{2,4})$/)) {
                    Layer.msg("请输入正确的" + text + "！");
                    element.focus();
                    return false;
                }
                var that = this;
                element.isValid(function (v) {
                    if (v) {
                        $(that).addClass("disabled", true).text("发送中...");
                        var data = {event: $(that).data("event")};
                        data[type] = element.val();
                        Frontend.api.ajax({url: $(that).data("url"), data: data}, function () {
                            clearInterval(si[type]);
                            var seconds = 60;
                            si[type] = setInterval(function () {
                                seconds--;
                                if (seconds <= 0) {
                                    clearInterval(si);
                                    $(that).removeClass("disabled").text("发送验证码");
                                } else {
                                    $(that).addClass("disabled").text(seconds + "秒后可再次发送");
                                }
                            }, 1000);
                        }, function () {
                            $(that).removeClass("disabled").text('发送验证码');
                        });
                    } else {
                        Layer.msg("请确认已经输入了正确的" + text + "！");
                    }
                });

                return false;
            });
			//修复不在iframe时layer-footer隐藏的问题
            if ($(".layer-footer").size() > 0 && self === top) {
                $(".layer-footer").show();
            }
            //tooltip和popover
            if (!('ontouchstart' in document.documentElement)) {
                $('body').tooltip({selector: '[data-toggle="tooltip"]'});
            }
            $('body').popover({selector: '[data-toggle="popover"]'});

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
        }
    };
    Frontend.api = $.extend(Fast.api, Frontend.api);
    //将Template渲染至全局,以便于在子框架中调用
    window.Template = Template;
    //将Moment渲染至全局,以便于在子框架中调用
    window.Moment = Moment;
    //将Frontend渲染至全局,以便于在子框架中调用
    window.Frontend = Frontend;

    Frontend.init();
    return Frontend;
});
