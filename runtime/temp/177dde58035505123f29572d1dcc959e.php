<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:60:"E:\seedata\public/../application/index\view\index\index.html";i:1552133864;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $site['name']; ?></title>
        <meta name="keywords" content="<?php echo $site['keyword']; ?>">
        <meta name="description" content="<?php echo $site['desc']; ?>">
        <link rel="shortcut icon" href="favicon.ico" />
        <!-- Bootstrap Core CSS -->
        <link rel="stylesheet" href="/assets/css/amazeui.min.css" />
        <link rel="stylesheet" href="/assets/css/app.css">
        <link rel="stylesheet" href="/assets/com/index.css">
    </head>
    <body class="theme-white">
        <header class="am-topbar am-topbar-fixed-top">
            <div class="am-fl tpl-header-logo">
                <a href="/"><img src="/assets/img/logo.png" alt=""></a>
            </div>
            <div class="tpl-header-fluid">
                <div class="am-fl tpl-header-navbar">
                    <ul>
                        <li class="am-text-sm"><a href="/">首页</a></li>
                        <li class="am-text-sm"><a href="/user/index.html">用户中心</a></li>
                        <li class="am-text-sm"><a href="/user/union.html">推广中心</a></li>
                        <li class="am-text-sm"><a href="https://www.kanshuju.com/" target="_blank">卖家工具箱</a></li>
                    </ul>
                </div>
                <div class="am-fr tpl-header-navbar">
                    <ul>
                        <?php if($user): ?>
                        <li class="am-text-sm"><a href="<?php echo url('user/index'); ?>">您好：<?php echo $user['username']; ?></a></li>
                        <li class="am-text-sm"><a href="<?php echo url('user/score'); ?>"><i class="am-icon-rmb"></i><?php echo $user['money']; ?></a></li>
                        <li class="am-text-sm"><a href="<?php echo url('user/logout'); ?>"><i class="am-icon-sign-out"></i>退出</a></li>
                        <?php else: ?>
                        <li class="am-text-sm"><a href="<?php echo url('user/login'); ?>"><i class="am-icon-key"></i> 登录</a></li>
                        <li class="am-text-sm"><a href="<?php echo url('user/register'); ?>"><i class="am-icon-user-plus"></i> 注册</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </header>
        <main class="content">
            <div class="pix_section pix-padding-v-85 pix-startup-intro" style="display: block; padding-top: 74px;">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 col-sm-12 column ui-droppable">
                            <div class="pix-content text-center">
                                <h1 class="pix-white pix-no-margin-top secondary-font">
                                    <span class="pix_edit_text"><strong><?php echo $site['name']; ?>卖家工具平台</strong></span>
                                </h1>
                                <h5 class="pix-slight-white pix-small2-width-text pix-margin-bottom-20 pix-no-margin-top">
                                    <span class="pix_edit_text"><?php echo $site['name']; ?>可以给您店铺真实流量，收藏加购等服务，支持淘宝、京东、拼多多等平台，还有免费的卖家工具</span>
                                </h5>
                                <div class="pix-padding-bottom-40">
                                    <?php if($user): ?>
                                    <a href="/user/index.html" class="btn green-bg pix-white btn-lg pix-margin-right-10">
                                        <span class="pix_edit_text"><b><i class="am-icon-user"></i> 用户中心 </b></span>
                                    </a>
                                    <?php else: ?>
                                    <a href="/user/login.html" class="btn green-bg pix-white btn-lg pix-margin-right-10">
                                        <span class="pix_edit_text"><b><i class="am-icon-key"></i> 立即登录 </b></span>
                                    </a>

                                    <a href="/user/register.html" class="btn dark-red-bg pix-white btn-lg" >
                                        <span class="pix_edit_text"><b><i class="am-icon-user-plus"></i> 免费注册</b></span>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pix_section gray-bg pix-padding-v-30 pix-padding-top-60">
                <div class="container">
                    <div class="row">
                        <div class="am-u-md-12 am-u-sm-12 column ui-droppable">
                            <div class="pix-content pix-padding-bottom-10 text-center">
                                <h2 class="pix-black-gray-dark pix-no-margin-top secondary-font">
                                    <span class="pix_edit_text"><b>平台特性</b></span>
                                </h2>
                                <div><span class="pix-bar blue-bg pix-margin-bottom-20 pix-margin-top-10"></span></div>
                                <p class="pix-black-gray-light big-text-20 pix-small-width-text">
                                    <span class="pix_edit_text"><?php echo $site['name']; ?>可以为您做些什么？</span>
                                </p>
                            </div>
                        </div>
                        <div class="am-u-md-4 am-u-sm-12 column ui-droppable">
                            <div class="pix-content pix-margin-v-20">
                                <div class="media">
                                    <div class="media-left media-top">
                                        <span class="pix-margin-bottom-10">
                                         <i class="am-icon-desktop big-icon-50 pix-blue"></i>
                                        </span>
                                    </div>
                                    <div class="media-body media-top">
                                        <div class="pix-padding-left-5">
                                            <h6 class="media-heading pix-light-black pix-margin-bottom-10">
                                              <span class="pix_edit_text">
                                               <strong>多平台流量</strong></span>
                                            </h6>
                                            <p class="pix-black-gray-light">
                                                <span class="pix_edit_text">支持淘宝、京东、拼多多流量。真实不过滤</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="am-u-md-4 am-u-sm-12 column ui-droppable">
                            <div class="pix-content pix-margin-v-20">
                                <div class="media">
                                    <div class="media-left media-top">
                                        <span class="pix-margin-bottom-10">
                                         <i class="am-icon-line-chart big-icon-50 pix-blue"></i>
                                        </span>
                                    </div>
                                    <div class="media-body media-top">
                                        <div class="pix-padding-left-5">
                                            <h6 class="media-heading pix-light-black pix-margin-bottom-10">
                                                <span class="pix_edit_text"><b>提升店铺权重</b></span>
                                            </h6>
                                            <p class="pix-black-gray-light">
                                                <span class="pix_edit_text">深度访问，收藏店铺宝贝。喜欢的人越多，权重提高越快</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="am-u-md-4 am-u-sm-12 column ui-droppable">
                            <div class="pix-content pix-margin-v-20">
                                <div class="media">
                                    <div class="media-left media-top">
                                        <span class="pix-margin-bottom-10">
                                         <i class="am-icon-bar-chart big-icon-50 pix-blue"></i>
                                        </span>
                                    </div>
                                    <div class="media-body media-top">
                                        <div class="pix-padding-left-5">
                                            <h6 class="pix-light-black pix-margin-bottom-10">
                                              <span class="pix_edit_text">
                                               <strong>优化转化率</strong></span>
                                            </h6>
                                            <p class="pix-black-gray-light">
                                                <span class="pix_edit_text">稳定转化率，提高真实权重。让宝贝健康发展</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="am-u-md-4 am-u-sm-12 column ui-droppable">
                            <div class="pix-content pix-margin-v-20">
                                <div class="media">
                                    <div class="media-left media-top">
                                        <span class="pix-margin-bottom-10">
                                         <i class="am-icon-fa big-icon-50 pix-blue"></i>
                                        </span>
                                    </div>
                                    <div class="media-body media-top">
                                        <div class="pix-padding-left-5">
                                            <h6 class="media-heading pix-light-black pix-margin-bottom-10">
                                               <span class="pix_edit_text">
                                               <strong>收藏加购</strong></span>
                                            </h6>
                                            <p class="pix-black-gray-light">
                                                <span class="pix_edit_text">店铺收藏，宝贝收藏，搜索进店，任意搭配，快递提高宝贝人气。</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="am-u-md-4 am-u-sm-12 column ui-droppable">
                            <div class="pix-content pix-margin-v-20">
                                <div class="media">
                                    <div class="media-left media-top">
                                        <span class="pix-margin-bottom-10">
                                         <i class="am-icon-truck big-icon-50 pix-blue"></i>
                                        </span>
                                    </div>
                                    <div class="media-body media-top">
                                        <div class="pix-padding-left-5">
                                            <h6 class="media-heading pix-light-black pix-margin-bottom-10">
                                              <span class="pix_edit_text">
                                               <strong>真实快递</strong></span>
                                            </h6>
                                            <p class="pix-black-gray-light">
                                                <span class="pix_edit_text">配合真实快递，快速助打造店铺爆款商品</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="am-u-md-4 am-u-sm-12 column ui-droppable">
                            <div class="pix-content pix-margin-v-20">
                                <div class="media">
                                    <div class="media-left media-top">
                                        <span class="pix-margin-bottom-10">
                                         <i class="am-icon-rmb big-icon-50 pix-blue"></i>
                                        </span>
                                    </div>
                                    <div class="media-body media-top">
                                        <div class="pix-padding-left-5">
                                            <h6 class="media-heading pix-light-black pix-margin-bottom-10">
                                              <span class="pix_edit_text">
                                               <strong>推广计划</strong></span>
                                            </h6>
                                            <p class="pix-black-gray-light">
                                                <span class="pix_edit_text">推广越多奖励越多，获取永久收益</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pix_section pix-padding-v-40" style="overflow: hidden">
                <div class="container">
                    <div class="row">
                        <div class="am-u-md-12 am-u-sm-12 column ui-droppable">
                            <div class="pix-content">
                                <div class="am-u-md-6">
                                    <div data-am-widget="list_news" class="am-list-news am-list-news-default" >
                                        <!--列表标题-->
                                        <div class="am-list-news-hd am-cf">
                                            <!--带更多链接-->
                                            <a href="/index/lists/ids/1.html" class="">
                                                <h2>帮助说明</h2>
                                                <span class="am-list-news-more am-fr">更多 &raquo;</span>
                                            </a>
                                        </div>
                                        <div class="am-list-news-bd">
                                            <ul class="am-list">
                                                <?php if(is_array($newslist) || $newslist instanceof \think\Collection || $newslist instanceof \think\Paginator): $i = 0; $__LIST__ = $newslist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nl): $mod = ($i % 2 );++$i;?>
                                                <li class="am-g am-list-item-dated">
                                                    <a href="/index/news/ids/<?php echo $nl['id']; ?>.html" class="am-list-item-hd"><?php echo $nl['title']; ?></a>
                                                    <span class="am-list-date"><?php echo date("Y-m-d",$nl['create_time']); ?></span>
                                                </li>
                                                <?php endforeach; endif; else: echo "" ;endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="am-u-md-6">
                                    <div data-am-widget="list_news" class="am-list-news am-list-news-default" >
                                        <!--列表标题-->
                                        <div class="am-list-news-hd am-cf">
                                            <!--带更多链接-->
                                            <a href="/index/lists/ids/0.html" class="">
                                                <h2>网站公告</h2>
                                                <span class="am-list-news-more am-fr">更多 &raquo;</span>
                                            </a>
                                        </div>
                                        <div class="am-list-news-bd">
                                            <ul class="am-list">
                                                <?php if(is_array($glist) || $glist instanceof \think\Collection || $glist instanceof \think\Paginator): $i = 0; $__LIST__ = $glist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gl): $mod = ($i % 2 );++$i;?>
                                                <li class="am-g am-list-item-dated">
                                                    <a href="/index/news/ids/<?php echo $gl['id']; ?>.html" class="am-list-item-hd"><?php echo $gl['title']; ?></a>
                                                    <span class="am-list-date"><?php echo date("Y-m-d",$gl['create_time']); ?></span>
                                                </li>
                                                <?php endforeach; endif; else: echo "" ;endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="am-footer am-footer-default">
            <div class="am-footer-miscs">
                <p>&copy; 2017-2018 <a href="/" target="_blank"><?php echo $site['name']; ?></a>. All Rights Reserved.</p>
            </div>
        </footer>
        <script src="/assets/com/jquery.min.js"></script>
        <script src="/assets/com/amazeui.min.js"></script>
    </body>
</html>