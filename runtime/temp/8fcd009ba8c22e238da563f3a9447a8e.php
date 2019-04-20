<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:64:"E:\seedata\public/../application/index\view\express\newadds.html";i:1543084200;s:50:"E:\seedata\application\index\view\common\meta.html";i:1548127895;s:52:"E:\seedata\application\index\view\common\script.html";i:1550733086;}*/ ?>
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
  <style>
.is-dialog .layer-footer {
  display: none;
}
</style>
    <form id="edit-form" class="form-horizontal <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>" role="form" data-toggle="validator" method="POST" action="">

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2">用户名:</label>
        <div class="col-xs-12 col-sm-8">
             <?php echo $user['username']; ?>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Dianpu'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-dianpu" class="form-control form-control" name="dianpu" type="text" value="" data-rule="required">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Fajianren'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-fajianren" class="form-control form-control" name="fajianren" type="text" value="" data-rule="required">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Shouji'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-shouji" class="form-control form-control" name="shouji" type="text" value="" data-rule="required;mobile">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2">地区:</label>
        <div class="col-xs-12 col-sm-8">
          <div class='control-relative'><input id="c-city" data-rule="required" class="form-control form-control" data-toggle="city-picker" name="rowqu" type="text" value=""></div>
     
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Address'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-address" class="form-control form-control" name="address" type="text" value="" data-rule="required">
        </div>
    </div>
      <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Ismr'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="radio">
            <?php if(is_array($ismrList) || $ismrList instanceof \think\Collection || $ismrList instanceof \think\Paginator): if( count($ismrList)==0 ) : echo "" ;else: foreach($ismrList as $key=>$vo): ?>
            <label for="row[ismr]-<?php echo $key; ?>"><input id="row[ismr]-<?php echo $key; ?>" name="ismr" type="radio" value="<?php echo $key; ?>" <?php if(in_array(($key), explode(',',"0"))): ?>checked<?php endif; ?> /> <?php echo $vo; ?></label> 
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
<script src="/assets/com/jquery.min.js"></script>
<script src="/assets/com/amazeui.min.js"></script>
<script src="/assets/com/amazeui.tagsinput.min.js"></script>
<script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-frontend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
