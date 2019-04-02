<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:72:"E:\seedata\public/../application/index\view\business\contend_detail.html";i:1553676182;s:53:"E:\seedata\application\index\view\layout\default.html";i:1552545932;s:50:"E:\seedata\application\index\view\common\meta.html";i:1548127895;s:53:"E:\seedata\application\index\view\common\sidenav.html";i:1553564999;s:52:"E:\seedata\application\index\view\common\script.html";i:1550733086;}*/ ?>
<!DOCTYPE html>
 <html>
        <head>
            <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">
<?php if(isset($keywords)): ?>
<meta name="keywords" content="<?php echo $keywords; ?>">
<?php endif; if(isset($description)): ?>
<meta name="description" content="<?php echo $description; ?>">
<?php endif; ?>
<meta name="author" content="FastAdmin">
<link rel="shortcut icon" href="/assets/img/favicon.ico" />
<link href="/assets/css/frontend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">
<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.js"></script>
  <script src="/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config: <?php echo json_encode($config); ?>
    };
</script>
            <link href="/assets/css/user.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">
            <link rel="stylesheet" href="/assets/css/amazeui.min.css" />
            <link rel="stylesheet" href="/assets/css/app.css">
        </head>
        <body class="theme-white">
        <header class="am-topbar am-topbar-fixed-top">
            <div class="am-fl tpl-header-logo">
                <a href="/"><img src="/assets/img/logo.png" alt=""></a>
            </div>
            <div class="tpl-header-fluid">
                   <?php if(in_array((\think\Request::instance()->action()), explode(',',"login,register"))): ?>
                <div class="am-fl tpl-header-navbar">
                    <ul>
                        <li class="am-text-sm"><a href="/">首页</a></li>
                        <li class="am-text-sm"><a href="/user/index.html">用户中心</a></li>
                        <li class="am-text-sm"><a href="/user/union.html">推广中心</a></li>
                        <li class="am-text-sm"><a href="https://www.kanshuju.com/" target="_blank">卖家工具箱</a></li>
                    </ul>
                </div>
                    <?php else: ?>
                <div class="am-fl tpl-header-switch-button am-icon-list"><span></span></div>
                    <?php endif; ?>
                <div class="am-fr tpl-header-navbar">
                    <ul>
                        <?php if($user): ?>
                        <li class="am-text-sm"><a href="<?php echo url('user/index'); ?>">您好：<?php echo $user['username']; ?></a></li>
                        <li class="am-text-sm"><a href="/recharge/recharge.html">充值</a></li>
                        <li class="am-text-sm"><a href="<?php echo url('user/score'); ?>"><i class="am-icon-rmb"></i><?php echo $user['money']; ?></a></li>
                        <li class="am-text-sm"><a href="<?php echo url('user/logout'); ?>"><i class="am-icon-sign-out"></i></a></li>
                        <?php else: ?>
                        <li class="am-text-sm"><a href="<?php echo url('user/login'); ?>"><i class="am-icon-key"></i> 登录</a></li>
                        <li class="am-text-sm"><a href="<?php echo url('user/register'); ?>"><i class="am-icon-user-plus"></i> 注册</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </header>
        <main class="content">
            <!-- 侧边导航栏 -->
<div class="left-sidebar">
    <!-- 菜单 -->
    <ul class="sidebar-nav">
        <li class="sidebar-nav-heading"> </li>
        <li class="sidebar-nav-link">
            <a href="/user/index.html" class="<?php echo $config['actionname']=='index'?'sub-active':''; ?>">
                <i class="am-icon-home sidebar-nav-link-logo"> </i> 用户首页
            </a>
        </li>
        <!-- <li class="sidebar-nav-link">
            <a href="javascript:;" class="sidebar-nav-sub-title">
                <i class="am-icon-plus-square sidebar-nav-link-logo"></i> 发布任务
            <?php if(in_array((\think\Request::instance()->action()), explode(',',"buy,uploads,tb,jd,pdd,dy,retask,redy"))): ?>
                <span class="am-icon-chevron-right am-fr am-margin-right-sm sidebar-nav-sub-ico sidebar-nav-sub-ico-rotate"></span>
            </a>
            <ul class="sidebar-nav sidebar-nav-sub" style="display: block;" >
            <?php else: ?>
                <span class="am-icon-chevron-right am-fr am-margin-right-sm sidebar-nav-sub-ico"></span>
            </a>
            <ul class="sidebar-nav sidebar-nav-sub" >
            <?php endif; ?>
                <li class="sidebar-nav-link">
                    <a href="/task/tb.html" class="<?php echo $config['actionname']=='tb'?'sub-active':''; ?>">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 淘宝任务
                    </a>
                </li>
                <li class="sidebar-nav-link">
                    <a href="/task/jd.html" class="<?php echo $config['actionname']=='jd'?'sub-active':''; ?>">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 京东任务
                    </a>
                </li>
                <li class="sidebar-nav-link">
                    <a href="/task/pdd.html" class="<?php echo $config['actionname']=='pdd'?'sub-active':''; ?>">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 拼多多任务
                    </a>
                </li>
                <li class="sidebar-nav-link">
                    <a href="/task/dy.html" class="<?php echo $config['actionname']=='dy'?'sub-active':''; ?>">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 抖音任务
                    </a>
                </li>
                <li class="sidebar-nav-link">
                    <a href="/express/buy.html" class="<?php echo $config['actionname']=='buy'?'sub-active':''; ?>">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 圆通任务
                    </a>
                </li>
                <li class="sidebar-nav-link">
                    <a href="/express/uploads.html" class="<?php echo $config['actionname']=='uploads'?'sub-active':''; ?>">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 批量下单
                    </a>
                </li>
            </ul>
        </li>
        <li class="sidebar-nav-link">
            <a href="javascript:;" class="sidebar-nav-sub-title">
                <i class="am-icon-list sidebar-nav-link-logo"></i> 任务记录
            <?php if(in_array((\think\Request::instance()->action()), explode(',',"exlist,ulist,tblist,jdlist,pddlist,dylist"))): ?>
                <span class="am-icon-chevron-right am-fr am-margin-right-sm sidebar-nav-sub-ico sidebar-nav-sub-ico-rotate"></span>
            </a>
            <ul class="sidebar-nav sidebar-nav-sub" style="display: block;" >
            <?php else: ?>
                <span class="am-icon-chevron-right am-fr am-margin-right-sm sidebar-nav-sub-ico"></span>
            </a>
            <ul class="sidebar-nav sidebar-nav-sub" >
            <?php endif; ?>
                <li class="sidebar-nav-link">
                    <a href="/task/tblist.html?task_tpe=1" class="<?php echo $config['actionname']=='tblist'?'sub-active':''; ?>">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 淘宝记录
                    </a>
                </li>
                <li class="sidebar-nav-link">
                    <a href="/task/jdlist.html?task_tpe=6" class="<?php echo $config['actionname']=='jdlist'?'sub-active':''; ?>">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 京东记录
                    </a>
                </li>
                <li class="sidebar-nav-link">
                    <a href="/task/pddlist.html?task_tpe=10" class="<?php echo $config['actionname']=='pddlist'?'sub-active':''; ?>">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 拼多多记录
                    </a>
                </li>
                <li class="sidebar-nav-link">
                    <a href="/task/dylist.html?task_tpe=12" class="<?php echo $config['actionname']=='dylist'?'sub-active':''; ?>">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 抖音记录
                    </a>
                </li>
                <li class="sidebar-nav-link">
                    <a href="/express/exlist.html" class="<?php echo $config['actionname']=='exlist'?'sub-active':''; ?>">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 单号管理
                    </a>
                </li>
                <li class="sidebar-nav-link">
                    <a href="/express/ulist.html" class="<?php echo $config['actionname']=='ulist'?'sub-active':''; ?>">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 导入记录
                    </a>
                </li>
            </ul>
        </li> -->
        <li class="sidebar-nav-link">
            <a href="javascript:;" class="sidebar-nav-sub-title">
                <i class="am-icon-user sidebar-nav-link-logo"></i> 用户中心
                <?php if(in_array((\think\Request::instance()->action()), explode(',',"adds,score,recharge,changepwd"))): ?>
                <span class="am-icon-chevron-right am-fr am-margin-right-sm sidebar-nav-sub-ico sidebar-nav-sub-ico-rotate"></span>
            </a>
            <ul class="sidebar-nav sidebar-nav-sub" style="display: block;" >
            <?php else: ?>
               <span class="am-icon-chevron-right am-fr am-margin-right-sm sidebar-nav-sub-ico"></span>
            </a>
            <ul class="sidebar-nav sidebar-nav-sub">
            <?php endif; ?>
                <li class="sidebar-nav-link">
                    <a href="/express/adds.html" class="<?php echo $config['actionname']=='adds'?'sub-active':''; ?>">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 发货地址
                    </a>
                </li>
                <li class="sidebar-nav-link">
                    <a href="/user/score.html" class="<?php echo $config['actionname']=='score'?'sub-active':''; ?>">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 资金明细
                    </a>
                </li>
                <li class="sidebar-nav-link">
                    <a href="/user/changepwd.html" class="<?php echo $config['actionname']=='changepwd'?'sub-active':''; ?>">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 修改密码
                    </a>
                </li>
                <li class="sidebar-nav-link">
                    <a href="/recharge/recharge.html" class="<?php echo $config['actionname']=='recharge'?'sub-active':''; ?>">
                        <span class="am-icon-angle-right sidebar-nav-link-logo"></span> 余额充值
                    </a>
                </li>
            </ul>
        </li>
        <li class="sidebar-nav-link">
            <a href="/Business/taobao" class="<?php echo $config['actionname']=='taobao'?'sub-active':''; ?>">
                <i class="sidebar-nav-link-logo"> <img src="/assets/img/tb.png" width="20" height="20"></i>
                淘宝排名查询
            </a>
        </li>
        <li class="sidebar-nav-link">
            <a href="/Business/jingdong" class="<?php echo $config['actionname']=='jingdong'?'sub-active':''; ?>">
                <i class="sidebar-nav-link-logo"> <img src="/assets/img/jd.png" width="20" height="20"></i>
                京东排名查询
            </a>
        </li>
        <li class="sidebar-nav-link">
            <a href="#">
               <i class="am-icon-exchange sidebar-nav-link-logo"></i> 生意参谋工具
            </a>
        </li>
        <li class="sidebar-nav-link">
                    <a href="/business/monitor" <?php if(in_array(($config['actionname']), explode(',',"monitor,search,chart"))): ?> class="sub-active" <?php endif; ?>>
                        <i class="am-icon-search sidebar-nav-link-logo"></i> 排名监控
                    </a>
                </li>
        <li class="sidebar-nav-link">
            <a href="/business/contend"  <?php if(in_array(($config['actionname']), explode(',',"contend,contend_detail"))): ?> class="sub-active" <?php endif; ?>>
                <i class="am-icon-line-chart sidebar-nav-link-logo"></i> 竞品监控
            </a>
        </li>
        <li class="sidebar-nav-link">
              <a href="/user/union.html" class="<?php echo $config['actionname']=='union'?'sub-active':''; ?>">
                 <i class="am-icon-group sidebar-nav-link-logo"></i> 推广赚钱
              </a>
        </li>
        <li class="sidebar-nav-link">
            <a href="/user/logout.html" >
                <i class="am-icon-sign-out sidebar-nav-link-logo"></i> 退出登录
            </a>
        </li>
    </ul>
</div>
<body onload="getContenList()">
<div class="tpl-content-wrapper">
  <div class="container-fluid am-cf">
    <div class="row">
      <div class="am-u-sm-12 am-u-md-12 am-u-lg-9">
        <p class="page-header-description">
        </p>
      </div>
    </div> 
  </div>
  <div class="row-content am-cf">
    <div class="row am-cf">
      <div class="am-u-md-10">
        <div class="widget am-cf">
        <div class="widget-head am-fr">查看商品监控详情</div>
        <div class="widget-head am-fr">
          <div class="widget-title am-fl w68">
            <div class="am-u-sm-2">
              <img style="float:left" src="" align="absmiddle" width="80px" id="xqimg">	
            </div>
            <div class="am-u-sm-10">
              <p id="xqtitle"> </p>
              <p>价格：<span id="xqprice"></span>； 销量：<span id="xqsale"></span>； 评价：<span id="xqping"></span>； </p>
              <p><img id="ptimg" src="" width="20" height="20">店铺：<span id="xqdian"></span>； 宝贝ID/SKU：<span id="xqsku"></span> </p>
            </div>

          </div>
          <div class="widget-function am-fr">
            <button type="button" class="am-btn am-btn-warning"  onclick="JavaScript:history.back(-1);"><span class="am-icon-undo"></span> 返回</button>
          </div>
        </div>
        <div class="widget-body am-fr">
          <div class="am-tabs" data-am-tabs="{noSwipe:1}">
            <ul class="am-tabs-nav am-nav am-nav-tabs">
              <li class="am-active"><a href="#tab1">标题监控</a></li>
              <li onclick="jpzs('goods_price')"><a href="#tab2">价格监控</a></li>
              <li onclick="jpzs('goods_sales_count')"><a href="#tab3">销量监控</a></li>
              <li onclick="jpzs('goods_comment')"><a href="#tab4">评价监控</a></li>
              <li onclick="getPic('ware_picture')"><a href="#tab5">主图监控</a></li>
            </ul>
            <div class="am-tabs-bd">
              <div class="am-tab-panel am-fade am-in am-active" id="tab1">
              标题监控
              </div>
              <div class="am-tab-panel am-fade" id="tab2">
                <div class="am-margin-xs tpl-login am-fl" style="height: 400px" id="pricecharts">
                价格走势图表
                </div>
              </div>
              <div class="am-tab-panel am-fade" id="tab3">
                <div class="am-margin-xs tpl-login am-fl" style="height: 400px" id="salesecharts">
                销量走势图表
                </div>
              </div>
              <div class="am-tab-panel am-fade" id="tab4">
                <div class="am-margin-xs tpl-login am-fl" style="height: 400px" id="commentecharts">
                评价走势图表
                </div>
              </div>
              <div class="am-tab-panel am-fade" id="tab5">
              主图监控
              </div>
            </div>
          </div>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
<script type="text/javascript">
function getUrlParam(name)
{
 var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
 var r = window.location.search.substr(1).match(reg);
 if(r!=null)return  unescape(r[2]); return null;
}
function getContenList(){
  var ids = getUrlParam('id');
  $.ajax({
    data:{ids:ids},
    type:'post',
    dataType:'json',
    url:'get_contend',
    success:function(res){
      var info = res.data;
      $('#xqprice').html(info.goods_price);
      $('#xqsale').html(info.sale_count);
      $('#xqping').html(info.remark_count);
      $('#xqimg').attr('src',info.goods_img);
      $('#xqdian').html(info.shop_name);
      $('#xqsku').html(info.goods_id);
      $('#xqtitle').html(info.goods_title);
      if(info.terrace=='京东'){
        $('#ptimg').attr('src','/assets/img/jd.png');
      }else if(info.terrace=='淘宝' || info.terrace=='天猫'){
        $('#ptimg').attr('src','/assets/img/tb.png');
      }
    }
  })
}
</script>

        </main>
        <footer class="am-footer am-footer-default">
            <div class="am-footer-miscs">
                <p>&copy; 2017-2018 <a href="/" target="_blank"><?php echo $site['name']; ?></a>. All Rights Reserved. <a href="https://www.miibeian.gov.cn" target="_blank"><?php echo $site['beian']; ?></a></p>
            </div>
        </footer>
        <script src="/assets/com/jquery.min.js"></script>
<script src="/assets/com/amazeui.min.js"></script>
<script src="/assets/com/amazeui.tagsinput.min.js"></script>
<script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-frontend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>

 <script type="text/javascript">
function OnlineOver(){
document.getElementById("aFloatTools_Show").style.display = "none";
document.getElementById("divFloatToolsView").style.display = "block";
document.getElementById("aFloatTools_Hide").style.display = "block";
document.getElementById("floatTools").style.width = "190px";
}
function OnlineOut(){
document.getElementById("aFloatTools_Show").style.display = "block";
document.getElementById("aFloatTools_Hide").style.display = "none";
document.getElementById("divFloatToolsView").style.display = "none";
document.getElementById("floatTools").style.width = "36px";
}
if(typeof(HTMLElement)!="undefined")    //给firefox定义contains()方法，ie下不起作用
{   
      HTMLElement.prototype.contains=function(obj)   
      {   
          while(obj!=null&&typeof(obj.tagName)!="undefind"){ //通过循环对比来判断是不是obj的父元素
   　　　　if(obj==this) return true;   
   　　　　obj=obj.parentNode;
   　　}   
          return false;   
      };   
}  
function hideMsgBox(theEvent){ //theEvent用来传入事件，Firefox的方式
　 if (theEvent){
　 var browser=navigator.userAgent; //取得浏览器属性
　 if (browser.indexOf("Firefox")>0){ //如果是Firefox
　　 if (document.getElementById('divFloatToolsView').contains(theEvent.relatedTarget)) { //如果是子元素
　　 return; //结束函式
} 
} 
if (browser.indexOf("MSIE")>0){ //如果是IE
if (document.getElementById('divFloatToolsView').contains(event.toElement)) { //如果是子元素
return; //结束函式
}
}
}
/*要执行的操作*/
document.getElementById("aFloatTools_Show").style.display = "block";
document.getElementById("aFloatTools_Hide").style.display = "none";
document.getElementById("divFloatToolsView").style.display = "none";
document.getElementById("floatTools").style.width = "36px";
}
</script>
<style type="text/css">
	.rides-cs {  font-size: 12px; background:#29a7e2; position: fixed; top: 200px; right: 0px; _position: absolute; z-index: 1500; border-radius:6px 0px 0 6px;}
	.rides-cs a { color: #00A0E9;}
	.rides-cs a:hover { color: #ff8100; text-decoration: none;}
	.rides-cs .floatL { width: 36px; float:left; position: relative; z-index:1;margin-top: 21px;height: 181px;}
	.rides-cs .floatL a { font-size:0; text-indent: -999em; display: block;}
	.rides-cs .floatR { width: 130px; float: left; padding: 5px; overflow:hidden;}
	.rides-cs .floatR .cn {background:#F7F7F7; border-radius:6px;margin-top:4px;}
	.rides-cs .cn .titZx{ font-size: 14px; color: #333;font-weight:600; line-height:24px;text-align:center;}
	.rides-cs .cn ul {padding:0px;}
	.rides-cs .cn ul li { padding-top: 3px; border-bottom: solid 1px #E6E4E4;overflow: hidden;text-align:center;}
	.rides-cs .cn ul li span { color: #777;}
	.rides-cs .cn ul li a{color: #777;}
	.rides-cs .cn ul li img { vertical-align: middle; display:inline}
	.rides-cs .btnOpen, .rides-cs .btnCtn {  position: relative; z-index:9; top:25px; left: 0;  background-image: url(/qqopen.png); background-repeat: no-repeat; display:block;  height: 146px; padding: 8px;}
	.rides-cs .btnOpen { background-position: 0 0;}
	.rides-cs .btnCtn { background-position: -37px 0;}
	.rides-cs ul li.top { border-bottom: solid #ACE5F9 1px;}
	.rides-cs ul li.bot { border-bottom: none;}
</style>
<div id="floatTools" class="rides-cs" style="height:286px;">
  <div class="floatL">
  	<a id="aFloatTools_Show" class="btnOpen" title="查看在线客服" style="top:20px;display:block" href="javascript:OnlineOver();">展开</a>
  	<a id="aFloatTools_Hide" class="btnCtn" title="关闭在线客服" style="top:20px;display:none" href="javascript:hideMsgBox()">收缩</a>
  </div>
  <div id="divFloatToolsView" class="floatR" style="display:none;height:277px;width:140px;">
    <div class="cn">
      <div class="titZx">在线咨询</div>
      <ul>                                            
        <li><span>客服</span> <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $site['qqhao']; ?>&site=qq&menu=yes"><img border="0" src="/button_111.gif" alt="点击这里给我发消息" title="点击这里给我发消息"/></a></li>
        <li><span>客服</span> <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $site['dailiqq']; ?>&site=qq&menu=yes"><img border="0" src="/button_111.gif" alt="点击这里给我发消息" title="点击这里给我发消息"/></a></li>
        <li><span>代理</span> <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $site['dailiqq']; ?>&site=qq&menu=yes"><img border="0" src="/button_111.gif" alt="点击这里给我发消息" title="点击这里给我发消息"/></a></li>
        <?php if($site['weixinkf'] != '0'): ?>
         <li><img src="	<?php echo $site['weixinkf']; ?>" width="130" height="130"></li>
        <?php endif; ?> 
        <li style="border:none;"><span>每天9:00-18:00在线</span></li>
      </ul>
    </div>
  </div>
</div>
    </body>

</html>