<?php

namespace app\api\controller;

use app\common\controller\Api;
/**
 * 电子面单接口
 */
class Express extends Api
{

    //如果$noNeedLogin为空表示所有接口都需要登录才能请求
    //如果$noNeedRight为空表示所有接口都需要验证权限才能请求
    //如果接口已经设置无需登录,那也就无需鉴权了
    //
    // 无需登录的接口,*表示全部
    protected $noNeedLogin = [];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = [];

	 /**
     * 获取电子面单
     * @ApiTitle    (获取电子面单)
     * @ApiSummary  (电子单号下单接口)
     * @ApiMethod   (POST)
	 * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token，注：token值请放在headers")
	 * @ApiParams   (name="send_order_no", type="string", required=true, description="订单号，不可重复。")
	 * @ApiParams   (name="platform", type="int", required=true, description="快递类型，1：圆通   3：圆通拼多多专用。")
	 * @ApiParams   (name="weight", type="Double", required=true, description="包裹重量，范围在0.1-40kg")
	 * @ApiParams   (name="goods", type="string", required=true, description="商品名称，30个字内")
	 * @ApiParams   (name="send_name", type="string", required=true, description="发件人")
	 * @ApiParams   (name="send_mphone", type="int", required=true, description="发件手机号码")
	 * @ApiParams   (name="send_province", type="string", required=true, description="发件人省")
	 * @ApiParams   (name="send_city", type="string", required=true, description="发件人市")
	 * @ApiParams   (name="send_district", type="string", required=true, description="发件人区")
	 * @ApiParams   (name="send_address", type="string", required=true, description="发件人详细地址")
	 * @ApiParams   (name="receiver_name", type="string", required=true, description="收货人")
	 * @ApiParams   (name="receiver_mphone", type="string", required=true, description="收货人号码")
	 * @ApiParams   (name="receiver_address", type="string", required=true, description="收货地址。格式：浙江省 杭州市 余杭区 文体路88号")
	 * @ApiReturnParams   (name="code", type="integer", required=true, description="状态值：1 为成功")
     * @ApiReturnParams   (name="msg", type="string", required=true, description="返回说明")
	 * @ApiReturnParams   (name="time", type="string", required=true, description="响应的时间戳")
     * @ApiReturnParams   (name="data", type="object", description="返回业务数据合集")
	 * @ApiReturnParams   (name="express_no", type="string", required=true, description="快递单号")
	 * @ApiReturnParams   (name="taskid", type="int", required=true, description="单号ID，请保存")
	 * @ApiReturnParams   (name="p", type="Double", required=true, description="扣费金额")
     * @ApiReturn   ({"code": 1,"msg": "","time": "1540347545","data": {"express_no": "80081082712","taskid": "98736","p": "3.0"}})
     */
   public function getyto()
    {
	   $express = model('Express');
	   $userid = $this->auth->id;
	   $weight = $this->request->request('weight');//包裹重量
	   $goods = $this->request->request('goods');//商品名
	   $expressid = $this->request->request('platform') ? $this->request->request('platform'):'1';//平台类型
	   $shouhuodi = $this->request->request('receiver_address');//收货地址
	   $shouhuoren = $this->request->request('receiver_name');//收货人
	   $shouhuohua = $this->request->request('receiver_mphone');//收货电话
	   $fajianren = $this->request->request('send_name');//发件人 
	   $send_province = $this->request->request('send_province');//发省
	   $send_city = $this->request->request('send_city');//发市
	   $send_district = $this->request->request('send_district');//发件区
	   $send_address = $this->request->request('send_address');//发件地址
	   $send_mphone = $this->request->request('send_mphone');//发件手机
	   $send_order_no = $this->request->request('send_order_no');//订单号
	   $out_order_no= $send_order_no ? $send_order_no : 'k'.time().mt_rand(1,100000);//唯一识别号
	   $isorder = \app\common\model\Express::where('out_order_no', $out_order_no)->find();
	   $usermoney = $this->auth->money;//用户余额
		if($expressid == 3){
			$task_frozen = getlv($this->auth->level)['ytopdd'];//用户的单价 
		}else{
			$task_frozen = getlv($this->auth->level)['yto'];//用户的单价
		}
		
	   if($usermoney < $task_frozen){
		// $ret =  \app\common\library\Sms::notice('15850734565', '15850734565', '360996');
		     $this->error('您的余额不足！',['money'=>$usermoney],110);
		        }
		
	   if ($isorder)
         {   
             $this->error('订单号:【'.$out_order_no.'】已经存在,请不要重复提交！',['express_no'=>$isorder['express_no'],'taskid'=>$isorder['id']],111);
         }
		
		  $dataa = array (
		    'token'=> config('yto.token'),
		    'platform' => $expressid,
            'send_order_no' => $out_order_no,
            'weight'=> $weight,
			'goods'=> $goods,
		    'from'=> 0,
		    'send_name'=> $fajianren,
		    'send_mphone'=> $send_mphone,
		    'send_province'=> $send_province,
		    'send_city'=> $send_city,
		    'send_district'=> $send_district,
		    'send_address'=> $send_address,
		    'receiver_name'=> $shouhuoren,
		    'receiver_mphone'=> $shouhuohua,
		    'receiver_address'=> $shouhuodi
        );
		$weighreq = sendRequest(config('yto.apiurl').'/api/express/getyto',$dataa, 'POST');
        $resa=json_decode($weighreq,true);
		
		if($resa['code'] != 1){	
			$this->error($resa['msg'],null,103);
		}
		$express_no = $resa['data']['express_no'];
		$taskid = $resa['data']['taskid'];		
	   \app\common\model\User::where('id', $userid)->setDec('money', $task_frozen);
		       $data['user_id']=$userid;
				$data['dianpu']=$fajianren;
				$data['express_no']=$express_no ? $express_no : '888';
				$data['out_order_no']=$out_order_no;
		        $data['taskid']=$taskid;
				$data['expressid']=$expressid;
				$data['addressid']=$send_mphone;
				$data['weight']=$weight;
				$data['price']=$task_frozen;
				$data['goods']=empty($goods) ? '宝贝' : $goods;
				$data['addressee']=$shouhuoren;
				$data['a_mphone']=$shouhuohua;
				$data['all_address']=$shouhuodi;
				$data['f_shen']=$send_province;
				$data['f_shi']=$send_city;
				$data['f_qu']=$send_district;
				$data['f_di']=$send_address;
				$data['from']=0;
                $express->save($data);
 
	  $this->success('提交成功', ['express_no' => $express_no,'taskid' => $express->id,'p' => $task_frozen]);
    }
     /**
     * 查询余额
     * @ApiTitle    (查询余额)
     * @ApiSummary  (获取账号余额)
     * @ApiMethod   (POST)
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token，注：token值请放在headers")
	 * @ApiReturnParams   (name="code", type="integer", required=true, description="状态值：1 为成功")
     * @ApiReturnParams   (name="msg", type="string", required=true, description="返回成功")
	 * @ApiReturnParams   (name="time", type="string", required=true, description="响应的时间戳")
     * @ApiReturnParams   (name="data", type="object", description="返回业务数据合集")
	 * @ApiReturnParams   (name="usermoney", type="string", required=true, description="账号所剩余额")
     * @ApiReturn   ({"code": 1,"msg": "","time": "1540347545","data": {"usermoney":88.88}})
     */
    public function getmoney()
    {
		//获取业务参数
        $this->success('返回成功', ['usermoney' => $this->auth->money]);
    }

}
