<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:63:"E:\seedata\public/../application/admin\view\pay\order\edit.html";i:1543673640;s:53:"E:\seedata\application\admin\view\layout\default.html";i:1536722460;s:50:"E:\seedata\application\admin\view\common\meta.html";i:1536722460;s:52:"E:\seedata\application\admin\view\common\script.html";i:1536722460;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<link rel="shortcut icon" href="/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.js"></script>
  <script src="/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config); ?>
    };
</script>
    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG && !$config['fastadmin']['multiplenav']): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo $vo['url']; ?>"><?php echo $vo['title']; ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                <form id="edit-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">

    <div class="form-group">
        <label for="c-product_id" class="control-label col-xs-12 col-sm-2"><?php echo __('Product_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-product_id" data-rule="" class="form-control" name="row[product_id]" type="text" value="<?php echo $row['product_id']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="c-qrcode_id" class="control-label col-xs-12 col-sm-2"><?php echo __('Qrcode_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-qrcode_id" data-rule="" class="form-control" name="row[qrcode_id]" type="text" value="<?php echo $row['qrcode_id']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="c-price" class="control-label col-xs-12 col-sm-2"><?php echo __('Price'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-price" class="form-control" step="0.01" name="row[price]" type="number" value="<?php echo $row['price']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="c-realprice" class="control-label col-xs-12 col-sm-2"><?php echo __('Realprice'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-realprice" class="form-control" step="0.01" name="row[realprice]" type="number" value="<?php echo $row['realprice']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="c-title" class="control-label col-xs-12 col-sm-2"><?php echo __('Title'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-title" class="form-control" name="row[title]" type="text" value="<?php echo $row['title']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="c-type" class="control-label col-xs-12 col-sm-2"><?php echo __('type'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-type" class="form-control" name="row[type]" type="text" value="<?php echo $row['type']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="c-paytime" class="control-label col-xs-12 col-sm-2"><?php echo __('Paytime'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-paytime" class="form-control datetimepicker" data-date-format="YYYY-MM-DD HH:mm:ss" data-use-current="true" name="row[paytime]" type="text" value="<?php echo datetime($row['paytime']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="c-status" class="control-label col-xs-12 col-sm-2"><?php echo __('Status'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            
            <div class="radio">
            <?php if(is_array($statusList) || $statusList instanceof \think\Collection || $statusList instanceof \think\Paginator): if( count($statusList)==0 ) : echo "" ;else: foreach($statusList as $key=>$vo): ?>
            <label for="row[status]-<?php echo $key; ?>"><input id="row[status]-<?php echo $key; ?>" name="row[status]" type="radio" value="<?php echo $key; ?>" <?php if(in_array(($key), is_array($row['status'])?$row['status']:explode(',',$row['status']))): ?>checked<?php endif; ?> /> <?php echo $vo; ?></label> 
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </div>

        </div>
    </div>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
        </div>
    </div>
</form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>