<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use think\Config;
use think\Cookie;
use think\Hook;
use think\Session;
use think\Validate;
use think\Db;


/**
 * 单号管理
 */
class Express extends Frontend
{

    protected $layout = 'default';
    protected $noNeedLogin = [''];
    protected $noNeedRight = ['*'];
    protected $searchFields = 'express_no,out_order_no,a_mphone';
    public function _initialize()
    {
        parent::_initialize();
		$auth = $this->auth;
    }

    /**
     * 空的请求
     * @param $name
     * @return mixed
     */
    public function _empty($name)
    {
        Hook::listen("user_request_empty", $name);
        return $this->view->fetch('user/' . $name);
    }

    /**
     * 下单
     */
    public function buy()
    {
		    $myaddress =Db::name('address')->where('statusswitch', 1)->where('user_id', $this->auth->id)->order('ismr desc')->select();
			$this->assign("myaddress",$myaddress);
		    $this->assign('empty','<option value>请先添加发货地址</option>');
		    $this->assign('title', '购买单号');
			// 渲染模板输出
        return $this->view->fetch();
    }
	/**
     * 批量下单
     */
    public function uploads()
    {
		    $myaddress =Db::name('address')->where('statusswitch', 1)->where('user_id', $this->auth->id)->order('ismr desc')->select();
			$this->assign("myaddress",$myaddress);
		    $this->assign('empty','<option value>请先添加发货地址</option>');
		    $this->assign('title', '批量购买单号');
			// 渲染模板输出
        return $this->view->fetch();
    }
	/**
     * 下单检测
     */
    public function checkyto()
    {
        if ($this->request->isPost()) {
            $addsid = $this->request->post('addsid');
            $expressid = $this->request->post('expressid');
            $weight1 = $this->request->post('weight1');
            $weight2 = $this->request->post('weight2');
            $goods = empty($this->request->post('goods')) ? '宝贝' : $this->request->post('goods');
            $addstext = $this->request->post('addstext');
            $address_arr = explode("\r\n",$addstext);
            $jil =  count($address_arr);
            if($jil > 500){
                $this->error("一次最多只能批量下500单");
            }
            $express = model('Express');
            $userid = $this->auth->id;
            if($expressid == 3){
                $task_frozen = getlv($this->auth->level)['ytopdd'];//用户的单价
            }else{
                $task_frozen = getlv($this->auth->level)['yto'];//用户的单价
            }
            $jians = 0-$task_frozen;
            $usermoney = $this->auth->money;//用户余额
            $sendadd =Db::name('address')->where('statusswitch', 1)->where('user_id',$userid)->where('id',$addsid)->find();

            //foreach ($address_arr as $value){
            foreach($address_arr as $key => $value){
                if($usermoney < $task_frozen){
                    $this->error("余额不足，成功发布了".$key."条记录",url('express/exlist/'));
                }
                $weight = round($weight1 + mt_rand() / mt_getrandmax() * ($weight2 - $weight1), 2);

                $shouarr = explode(",",$value);
                $date = date('mdH',time());
                $out_order_no = md5($shouarr[1].$shouarr[2].$date.$userid);
                $is_out_order_no = $express->where('out_order_no',$out_order_no)->find();
                if($is_out_order_no){
                    $this->error('请不要重复提交订单',url('express/exlist/'));
                }
                $dataa = array (
                    'token'=> config('yto.token'),
                    'platform' => $expressid,
                    'send_order_no' => $out_order_no,
                    'weight'=> $weight,
                    'goods'=> $goods,
                    'from'=> 0,
                    'send_name'=> $sendadd['fajianren'],
                    'send_mphone'=> $sendadd['shouji'],
                    'send_province'=> $sendadd['a_province'],
                    'send_city'=> $sendadd['city'],
                    'send_district'=> $sendadd['area'],
                    'send_address'=> $sendadd['address'],
                    'receiver_name'=> $shouarr[0],
                    'receiver_mphone'=> $shouarr[1],
                    'receiver_address'=> $shouarr[2]
                );
                $weighreq = sendRequest(config('yto.apiurl').'/api/express/getyto',$dataa, 'POST');
                $resa=json_decode($weighreq,true);
                if($resa['code'] != 1){
                    $this->error("只发布了".$key."条记录。".$resa['msg'],url('express/exlist/'));
                }
                $express_no = $resa['data']['express_no'];
                $taskid = $resa['data']['taskid'];
                $datas =[];
                $datas[$key] =[
                    'user_id'  =>  $userid,
                    'dianpu' =>  $sendadd['dianpu'],
                    'express_no'  => $express_no ? $express_no : '888' ,
                    'out_order_no'  =>  $out_order_no,
                    'taskid'  => $taskid,
                    'expressid'  => $expressid ,
                    'addressid'  =>  $addsid,
                    'weight'  =>  $weight,
                    'price'  =>  $task_frozen,
                    'goods'  =>  $goods ,
                    'addressee'  => $shouarr[0] ,
                    'a_mphone'  => $shouarr[1] ,
                    'all_address'  =>  $shouarr[2],
                    'f_shen'  =>  $sendadd['a_province'],
                    'f_shi'  =>  $sendadd['city'],
                    'f_qu'  => $sendadd['area'] ,
                    'f_di'  => $sendadd['address'] ,
                    'from'  => 0
                ];
                $logid = $express->saveAll($datas);
                $usermoney = $usermoney-$task_frozen;
                \app\common\model\User::score(0,$jians,$userid,'购买快递:'.$logid[$key]['id']);
            }//循环结束
            $this->success("发布成功".($key+1)."条",url('Express/exlist'));
        }

    }
	 /**
     * 批量处理
     */
    public function import()
    {
        set_time_limit(0);
        if ($this->request->isPost()) {
            $file =$this->request->post('avatar');
            if (!$file) {
                $this->error('请上传需要导入的表格！支持csv,xls,xlsx格式！');
            }
            $filePath = ROOT_PATH . DS . 'public' . DS . $file;
            if (!is_file($filePath)) {
                $this->error('上传的表格不存在，请核实');
            }

            $PHPReader = new \PHPExcel_Reader_Excel2007();
            if (!$PHPReader->canRead($filePath)) {
                $PHPReader = new \PHPExcel_Reader_Excel5();
                if (!$PHPReader->canRead($filePath)) {
                    $PHPReader = new \PHPExcel_Reader_CSV();
                    $PHPReader->setInputEncoding('GBK');
                    if (!$PHPReader->canRead($filePath)) {
                        $this->error(__('Unknown data format'));
                    }
                }
            }
            $addsid = $this->request->post('addsid');
            $expressid = $this->request->post('expressid');
            $weight1 = $this->request->post('weight1');
            $weight2 = $this->request->post('weight2');

            $goods = empty($this->request->post('goods')) ? '宝贝' : $this->request->post('goods');

            $express = model('Express');
            $userid = $this->auth->id;
            if($expressid == 3){
                $task_frozen = getlv($this->auth->level)['ytopdd'];//用户的单价
            }else{
                $task_frozen = getlv($this->auth->level)['yto'];//用户的单价
            }
            $jians = 0-$task_frozen;
            $usermoney = $this->auth->money;//用户余额
            $sendadd =Db::name('address')->where('statusswitch', 1)->where('user_id',$userid)->where('id',$addsid)->find();

            $PHPExcel = $PHPReader->load($filePath); //加载文件
            $currentSheet = $PHPExcel->getSheet(0);  //读取文件中的第一个工作表
            // $allColumn = $currentSheet->getHighestDataColumn(); //取得最大的列号
            $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
            if($allRow > 200){
                $this->error("单次表格导入记录数不得大于200条，当前记录数为：".$allRow);
            }
            $pttype = $currentSheet->getCell("A1")->getValue();//是什么平台表格
            $uptime = time();
            $leijia = '';
            $num = 0;
            for($i=2;$i<=$allRow;$i++)
            {
                if($usermoney < $task_frozen){
                    $upinfo=Db::name('upinfo');
                    $info['user_id'] = $userid;
                    $info['exnum'] = $allRow-1;//要插入数据库的总记录数
                    $info['oknum'] = $i-2;//成功插入的数量
                    $info['tableid'] = $uptime;//此次任务的id
                    $info['expressid'] = $expressid;
                    $info['vars'] = '余额不足';
                    $upinfo->insert($info);
                    $this->error("余额不足，成功发布了".($i-2)."条记录",url('Express/ulist'));
                }
                $weight = round($weight1 + mt_rand() / mt_getrandmax() * ($weight2 - $weight1), 2);
                if($pttype=='订单编号'){//淘宝
                    $out_order_no = $currentSheet->getCell("A".$i)->getValue();//订单号
                    $address_ren =  $currentSheet->getCell("O".$i)->getValue();//收件人
                    $address_array = preg_replace('/(，)|(,)/','',$currentSheet->getCell("P".$i)->getValue());//收货地址
                    $address_hao = preg_replace('/\W/','',$currentSheet->getCell("S".$i)->getValue());//号码
                }else if($pttype=='商品'){//拼多多
                    $out_order_no =  $currentSheet->getCell("B".$i)->getValue();
                    $address_ren =  $currentSheet->getCell("O".$i)->getValue();//收件人
                    $address_hao = preg_replace('/\W/','',$currentSheet->getCell("P".$i)->getValue());//收件号码
                    $add_s =  $currentSheet->getCell("Q".$i)->getValue();//省
                    $add_c =  $currentSheet->getCell("R".$i)->getValue();//市
                    $add_a =  $currentSheet->getCell("S".$i)->getValue();//区
                    $add_d =  $currentSheet->getCell("T".$i)->getValue();//地址
                    $address_array = $add_s.' '.$add_c.' '.$add_a.' '.$add_d;//收件地址

                }else{//模板
                    $out_order_no =  $currentSheet->getCell("A".$i)->getValue();
                    $address_array = preg_replace('/(，)|(,)/','',$currentSheet->getCell("B".$i)->getValue());//收件地址
                    $address_ren =  $currentSheet->getCell("C".$i)->getValue();//收件人
                    $address_hao = preg_replace('/\W/','',$currentSheet->getCell("D".$i)->getValue());//收件号码
                }
                $out_order_no = replaceSpecialChar($out_order_no);
                $isorder = \app\common\model\Express::where('out_order_no', $out_order_no)->find();
                if ($isorder)
                {
                    $leijia = $leijia.'第'.($i).'行地址订单号'.$isorder['out_order_no'].'重复,快递号为：'.$isorder['express_no'];
                    continue;
                    //$this->error('第'.($i).'条地址的订单号:【'.$out_order_no.'】已经存在,请不要重复提交！对应的快递单号为：'.$isorder['express_no']);
                }

                $dataa = array (
                    'token'=> config('yto.token'),
                    'platform' => $expressid,
                    'send_order_no' => $out_order_no,
                    'weight'=> $weight,
                    'goods'=> $goods,
                    'from'=> 1,
                    'send_name'=> $sendadd['fajianren'],
                    'send_mphone'=> $sendadd['shouji'],
                    'send_province'=> $sendadd['a_province'],
                    'send_city'=> $sendadd['city'],
                    'send_district'=> $sendadd['area'],
                    'send_address'=> $sendadd['address'],
                    'receiver_name'=> $address_ren,
                    'receiver_mphone'=> $address_hao,
                    'receiver_address'=> $address_array
                );
                $weighreq = sendRequest(config('yto.apiurl').'/api/express/getyto',$dataa, 'POST');
                $resa=json_decode($weighreq,true);
                if($resa['code'] != 1){
                    $leijia = $leijia.',第'.($i).'行地址有误：'.$resa['msg'];
                    continue;
                    //$this->error("只发布了".($i-2)."条记录。".$resa['message']);
                }
                $express_no = $resa['data']['express_no'];
                $taskid = $resa['data']['taskid'];
                $datas =[];
                $datas[$i] =[
                    'user_id'  =>  $userid,
                    'dianpu' =>  $sendadd['dianpu'],
                    'express_no'  => $express_no ? $express_no : '888' ,
                    'out_order_no'  =>  $out_order_no,
                    'taskid'  => $taskid,
                    'expressid'  => $expressid ,
                    'addressid'  =>  $addsid,
                    'weight'  =>  $weight,
                    'price'  =>  $task_frozen,
                    'goods'  =>  $goods ,
                    'addressee'  => $address_ren ,
                    'a_mphone'  => $address_hao ,
                    'all_address'  => $address_array,
                    'f_shen'  =>  $sendadd['a_province'],
                    'f_shi'  =>  $sendadd['city'],
                    'f_qu'  => $sendadd['area'] ,
                    'f_di'  => $sendadd['address'] ,
                    'tableid'  => $uptime ,
                    'from'  => 1
                ];
                $logid = $express->saveAll($datas);
                $usermoney = $usermoney-$task_frozen;
                \app\common\model\User::score(0,$jians,$userid,'批量购买快递:'.$logid[$i]['id']);
                $num = $num+1;
            }
            $upinfo=Db::name('upinfo');
            $info['user_id'] = $userid;
            $info['exnum'] = $allRow-1;//要插入数据库的总记录数
            $info['oknum'] = $num;//成功插入的数量
            $info['tableid'] = $uptime;//此次任务的id
            $info['expressid'] = $expressid;
            $info['vars'] = $leijia=='' ?'全部导入成功':$leijia;
            $upinfo->insert($info);
            $this->success("发布成功".$num."条",url('Express/ulist'));
            //$this->error($file);
        }
    }
    /**
     * 获取快递类型
     * @Author   zsw
     * @DataTime 2018-12-03T15:16:05+0800
     * @return   [type]                   [description]
     */
    public function get_kuaidi() 
    {
        $expressid = input('expressid');
        switch ($expressid)
        {
            case 1:
            return getlv($this->auth->level)['yto'];
            break;
            case 2:
            return getlv($this->auth->level)['zto'];
            break;  
            case 3:
            return getlv($this->auth->level)['ytopdd'];
            break;
            default:
            return getlv($this->auth->level)['yto'];
            break;
        }
    }	
    /**
     * 单号列表
     */
   public function exlist()
    {
		//设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = model('Express')
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = model('Express')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
		$this->assign('title', '单号记录');
        return $this->view->fetch();
    }
    public function ulist()
    {
		//设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = Db::name('upinfo')
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = Db::name('upinfo')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
		$this->assign('title', '发货地址');
        return $this->view->fetch();
    }
 public function export()
   {
	    $filenames = date("Y-m-d H-i",time());
        header( "Content-type:   application/octet-stream "); 
        header( "Accept-Ranges:   bytes "); 
        header( "Content-Disposition:   attachment;   filename=".$filenames."发货单号.txt "); 
        header( "Expires:   0 "); 
        header( "Cache-Control:   must-revalidate,   post-check=0,   pre-check=0 "); 
        header( "Pragma:   public "); 
        $express=model('express');
        $tableid = input('tableid');
        $map['user_id']= $this->auth->id;
        $map['tableid'] = $tableid;
        $map['express_no'] = ['neq',0];
        $map['from'] = 1;
        $list=$express->where($map)->order('create_time desc')->select();
        if(empty($list)){
            echo "数据为空，目前只能导出表格批量导入的订单";
        }else{
            foreach($list as $val){
                if($val['expressid']==1){
                    $kname = '圆通';
                }elseif($val['expressid']==5){
                    $kname = '韵达';
                }else{
                    $kname = '未知';
                }
                echo trim($val['out_order_no']).",".$val['express_no'].",".$kname."\r\n";
            }
        }
   }	
/**
     * 地址列表
     */
   public function adds()
    {
		//设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = Db::name('address')
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = Db::name('address')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
		$this->assign('title', '发货地址');
        return $this->view->fetch();
    }
public function newadds(){
	        $fajianren = $this->request->request('fajianren');
			$dianpu = $this->request->request('dianpu');
			$shouji = $this->request->request('shouji');
			$ismr = $this->request->request('ismr');
			$rowqu = $this->request->request('rowqu');
			$address = $this->request->request('address');
		    $alladds = explode('/',$rowqu);
	
	$ismrList = ['0' => __('Ismr 0'),'1' => __('Ismr 1')];
	$this->view->assign("ismrList", $ismrList);
	$this->view->engine->layout(false);
	if ($this->request->isAjax()) {
		        $data['user_id']=$this->auth->id;
		        $data['fajianren']=$fajianren;
				$data['dianpu']=$dianpu;
				$data['shouji']=$shouji;
				$data['ismr']=$ismr;
				$data['a_province']=$alladds[0];
				$data['city']=$alladds[1];
				$data['area']=$alladds[2];
				$data['address']=$address;
		        $data['create_time']=time();
               Db::name('address')->insert($data);
            $this->success("添加成功");
        }
	return $this->view->fetch();
}	
public function editdds($ids = NULL)
    {
		    $fajianren = $this->request->request('fajianren');
			$ismr = $this->request->request('ismr');
			$rowqu = $this->request->request('rowqu');
			$address = $this->request->request('address');
		    $alladds = explode("/",$rowqu);
        $row= Db::name('address')->where('id', $ids)->where('user_id', $this->auth->id)->find();
		if (!$row)
            $this->error(__('No Results were found'));
	    $ismrList = ['0' => __('Ismr 0'),'1' => __('Ismr 1')];
		
	    $this->view->assign("row", $row);
		$this->view->assign("ismrList", $ismrList);
		$this->view->engine->layout(false);
		if ($this->request->isAjax()) {
			 
			Db::name('address')->where('id', $ids)->update(['fajianren' => $fajianren,'address' => $address,'ismr' => $ismr,'a_province' =>$alladds[0],'city' => $alladds[1],'area' => $alladds[2]]);
            $this->success("修改成功");
        }
		return $this->view->fetch();
		
		  
    } 
 
}
