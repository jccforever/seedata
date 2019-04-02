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
 * 任务管理
 */
class Task extends Frontend
{

    protected $layout = 'default';
    protected $noNeedLogin = ['puzttims'];
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
    public function tb($ids = NULL)
    {
        switch($ids){
            case 1:
                $userprice = getlv($this->auth->level)['tbapp'];//用户的单价
                break;
            case 2:
                $userprice = getlv($this->auth->level)['scjg'];//用户的单价
                break;
            case 3:
                $userprice = getlv($this->auth->level)['scjg'];//用户的单价
                break;
            case 4:
                $userprice = getlv($this->auth->level)['scjg'];//用户的单价
                break;
            case 5:
                $userprice = getlv($this->auth->level)['tbpc'];//用户的单价
                break;
            default:
                $ids = 1;
                $userprice = getlv($this->auth->level)['tbapp'];//用户的单价
                break;
        }
            $this->assign('ids',$ids);
            $this->assign('userpric',$userprice);
		    $this->assign('title', '发布淘宝任务');
			// 渲染模板输出
            return $this->view->fetch();
    }
    public function retask($ids = NULL)
    {
        $task = model('Task');
        $userid = $this->auth->id;
        $findsql = $task->where('id',$ids)->where('user_id',$userid)->find();
        if(!$findsql){
            $this->error('任务不存在！'.$ids);
        }
        switch($findsql['task_tpe']){
            case 1:
                $rename = '手淘流量';
                $userprice = getlv($this->auth->level)['tbapp'];//用户的单价
                break;
            case 2:
                $rename = '手淘收藏';
                $userprice = getlv($this->auth->level)['scjg'];//用户的单价
                break;
            case 3:
                $rename = '手淘加购';
                $userprice = getlv($this->auth->level)['scjg'];//用户的单价
                break;
            case 4:
                $rename = '店铺收藏';
                $userprice = getlv($this->auth->level)['scjg'];//用户的单价
                break;
            case 5:
                $rename = '淘宝pc流量';
                $userprice = getlv($this->auth->level)['tbpc'];//用户的单价
                break;
            case 6:
                $rename = '京东流量';
                $userprice = getlv($this->auth->level)['jdapp'];//用户的单价
                break;
            case 7:
                $rename = '京东收藏';
                $userprice = getlv($this->auth->level)['scjg'];//用户的单价
                break;
            case 8:
                $rename = '京东加购';
                $userprice = getlv($this->auth->level)['scjg'];//用户的单价
                break;
            case 9:
                $rename = '店铺关注';
                $userprice = getlv($this->auth->level)['scjg'];//用户的单价
                break;
            case 10:
                $rename = '拼多多流量';
                $userprice = getlv($this->auth->level)['pddapp'];//用户的单价
                break;
            case 11:
                $rename = '拼多多收藏';
                $userprice = getlv($this->auth->level)['scjg'];//用户的单价
                break;
            default:
                $rename = '手淘流量';
                $userprice = getlv($this->auth->level)['tbapp'];//用户的单价
                break;
        }
        $this->assign('ids',$findsql['task_tpe']);
        $this->assign("read",$findsql);
        $this->assign('userpric',$userprice);
        $this->assign('rename',$rename);
        $this->assign('title', '重发任务');
        // 渲染模板输出
        return $this->view->fetch();
    }

    public function jd($ids = NULL)
    {
        switch($ids){
            case 6:
                $userprice = getlv($this->auth->level)['jdapp'];//用户的单价
                break;
            case 7:
                $userprice = getlv($this->auth->level)['scjg'];//用户的单价
                break;
            case 8:
                $userprice = getlv($this->auth->level)['scjg'];//用户的单价
                break;
            case 9:
                $userprice = getlv($this->auth->level)['scjg'];//用户的单价
                break;
            default:
                $ids = 6;
                $userprice = getlv($this->auth->level)['jdapp'];//用户的单价
                break;
        }
        $this->assign('ids',$ids);
        $this->assign('userpric',$userprice);
        $this->assign('title', '发布京东任务');
        // 渲染模板输出
        return $this->view->fetch();
    }
    public function pdd($ids = NULL)
    {
        switch($ids){
            case 10:
                $userprice = getlv($this->auth->level)['pddapp'];//用户的单价
                break;
            case 11:
                $userprice = getlv($this->auth->level)['scjg'];//用户的单价
                break;
            default:
                $ids = 10;
                $userprice = getlv($this->auth->level)['pddapp'];//用户的单价
                break;
        }
        $this->assign('ids',$ids);
        $this->assign('userpric',$userprice);
        $this->assign('title', '发布拼多多任务');
        // 渲染模板输出
        return $this->view->fetch();
    }
    public function dy($ids = NULL)
    {
        switch($ids){
            case 12:
                $userprice = getlv($this->auth->level)['dyfs'];//用户的单价
                break;
            case 13:
                $userprice = getlv($this->auth->level)['dyfs'];//用户的单价
                break;
            case 14:
                $userprice = getlv($this->auth->level)['dyfs'];//用户的单价
                break;
            case 15:
                $userprice = getlv($this->auth->level)['dybf'];//用户的单价
                break;
            case 16:
                $userprice = getlv($this->auth->level)['dyfx'];//用户的单价
                break;
            default:
                $ids = 12;
                $userprice = getlv($this->auth->level)['pddapp'];//用户的单价
                break;
        }
        $this->assign('ids',$ids);
        $this->assign('userpric',$userprice);
        $this->assign('title', '发布抖音任务');
        // 渲染模板输出
        return $this->view->fetch();
    }
    public function redy($ids = NULL)
    {
        $task = model('Task');
        $userid = $this->auth->id;
        $findsql = $task->where('id',$ids)->where('user_id',$userid)->find();
        if(!$findsql){
            $this->error('任务不存在！'.$ids);
        }
        switch($findsql['task_tpe']){
            case 12:
                $rename = '抖音粉丝';
                $userprice = getlv($this->auth->level)['dyfs'];//用户的单价
                break;
            case 13:
                $rename = '抖音点赞';
                $userprice = getlv($this->auth->level)['dyfs'];//用户的单价
                break;
            case 14:
                $rename = '抖音评论';
                $userprice = getlv($this->auth->level)['dyfs'];//用户的单价
                break;
            case 15:
                $rename = '抖音播放';
                $userprice = getlv($this->auth->level)['dybf'];//用户的单价
                break;
            case 16:
                $rename = '抖音分享';
                $userprice = getlv($this->auth->level)['dyfx'];//用户的单价
                break;
            default:
                $rename = '抖音粉丝';
                $userprice = getlv($this->auth->level)['pddapp'];//用户的单价
                break;
        }
        $this->assign('ids',$findsql['task_tpe']);
        $this->assign("read",$findsql);
        $this->assign('rename',$rename);
        $this->assign('userpric',$userprice);
        $this->assign('title', '重发抖音任务');
        // 渲染模板输出
        return $this->view->fetch();
    }
	/**
     * 下单检测
     */
    public function check()
	{
		if ($this->request->isPost()) {
            $task_name = $this->request->post('task_name');
            $task_url = $_POST['task_url'];
            $params = $this->request->post("row/a");
            $task_c = implode(',',$this->request->post('task_c/a'));
            $goodstime = $this->request->post('goodstime');
            $days = $this->request->post('days');
            $isfs = $this->request->post('isfs');
            $tpe = $this->request->post('tpe');//任务类型
            switch($tpe){
                case 1:
                    $apitype =1;
                    $typename = '手淘流量';
                    $userprice = getlv($this->auth->level)['tbapp'];//用户的单价
                    $taobaoid = tbid($task_url);//宝贝ID
                    $jupurl = '/task/tblist.html?task_tpe=1';
                    break;
                case 2:
                    $apitype = 2;
                    $typename = '手淘收藏';
                    $userprice = getlv($this->auth->level)['scjg'];//用户的单价
                    $taobaoid = tbid($task_url);//宝贝ID
                    $jupurl = '/task/tblist.html?task_tpe=2';
                    break;
                case 3:
                    $apitype =3;
                    $typename = '手淘加购';
                    $userprice = getlv($this->auth->level)['scjg'];//用户的单价
                    $taobaoid = tbid($task_url);//宝贝ID
                    $jupurl = '/task/tblist.html?task_tpe=3';
                    break;
                case 4:
                    $apitype =4;
                    $typename = '店铺收藏';
                    $userprice = getlv($this->auth->level)['scjg'];//用户的单价
                    $taobaoid = 100000;//宝贝ID
                    $jupurl = '/task/tblist.html?task_tpe=4';
                    break;
                case 5:
                    $apitype =5;
                    $typename = '淘宝pc流量';
                    $userprice = getlv($this->auth->level)['tbpc'];//用户的单价
                    $taobaoid = tbid($task_url);//宝贝ID
                    $jupurl = '/task/tblist.html?task_tpe=5';
                    break;
                case 6:
                    $apitype =6;
                    $typename = '京东流量';
                    $userprice = getlv($this->auth->level)['jdapp'];//用户的单价
                    $taobaoid = jdid($task_url);//宝贝ID
                    $jupurl = '/task/jdlist.html?task_tpe=6';
                    break;
                case 7:
                    $apitype =7;
                    $typename = '京东收藏';
                    $userprice = getlv($this->auth->level)['scjg'];//用户的单价
                    $taobaoid = jdid($task_url);//宝贝ID
                    $jupurl = '/task/jdlist.html?task_tpe=7';
                    break;
                case 8:
                    $apitype =8;
                    $typename = '京东加购';
                    $userprice = getlv($this->auth->level)['scjg'];//用户的单价
                    $taobaoid = jdid($task_url);//宝贝ID
                    $jupurl = '/task/jdlist.html?task_tpe=8';
                    break;
                case 9:
                    $apitype =9;
                    $typename = '京东关注';
                    $userprice = getlv($this->auth->level)['scjg'];//用户的单价
                    $taobaoid = 200000;//宝贝ID
                    $jupurl = '/task/jdlist.html?task_tpe=9';
                    break;
                case 10:
                    $apitype =10;
                    $typename = '拼多多流量';
                    $userprice = getlv($this->auth->level)['pddapp'];//用户的单价
                    $taobaoid = pddid($task_url);//宝贝ID
                    $jupurl = '/task/pddlist.html?task_tpe=10';
                    break;
                case 11:
                    $apitype =11;
                    $typename = '拼多多收藏';
                    $userprice = getlv($this->auth->level)['scjg'];//用户的单价
                    $taobaoid = pddid($task_url);//宝贝ID
                    $jupurl = '/task/pddlist.html?task_tpe=11';
                    break;
                default:
                    $apitype =1;
                    $typename = '手淘流量';
                    $userprice = getlv($this->auth->level)['tbapp'];//用户的单价
                    $taobaoid = tbid($task_url);//宝贝ID
                    $jupurl = '/task/tblist.html?task_tpe=1';
                    break;
            }
            if(!$taobaoid){ $this->error("输入的连接不正确！");}
            if($isfs == 'day' && date('H') > 18){$this->error('已经过了18点，无法发布当前方式，请选择其他完成方式');}
            if($days ==1){
                $etimes = strtotime(date("Y/m/d 23:59:59",time()));
            }else{
                $etimes = time()+60*60*24*$days;
            }
            if($goodstime == 4){
                $gt = rand(40,50); //停留时间
                $price = $userprice;//用户单价
            }else if($goodstime == 6){
                $gt = rand(50,60);
                $price = $userprice+0.03;//用户单价
            }else if($goodstime == 8){
                $gt = rand(60,80);
                $price = $userprice+0.05;//用户单价
            }else if($goodstime == 10){
                $gt = rand(80,100);
                $price = $userprice+0.07;//用户单价
            }else{
                $gt =rand(30,39);
                $price = $userprice;//用户单价
            }
            if ($params) {
                $task = model('Task');
                $sum = 0;
                $tasknfo = $params['task_info'];
                $userid = $this->auth->id;
                $usermoney =$this->auth->money;//用户余额
                foreach($tasknfo as $k=>$val){
                    $hours = getTaskHour($val["num"],$isfs);//每小时任务量
                    $task_money = $price*$val["num"]*$days ;//任务总价
                    $jians = 0-$task_money;
                    if($usermoney < $task_money){
                        $this->error("余额不足，成功发布了".$k."条关键词",$jupurl);
                    }
                    $taskdata = array (
                        'token'=> config('yto.token'),
                        'task_name' => $task_name ? $task_name : $val["keys"].'-'.date("m/d"),
                        'task_url' => $task_url,
                        'task_key'=> $val["keys"],
                        'task_num'=> $val["num"],
                        'task_c'=> $task_c,
                        'goodstime'=> $goodstime,
                        'days'=> $days,
                        'isfs'=> $isfs,
                        'tpe'=> $apitype
                    );
                    $result = sendRequest(config('yto.apiurl').'/api/task/taskadd',$taskdata, 'POST');
                    $resa=json_decode($result,true);
                    $taskid = $resa['data']['taskid'];
                    if($resa['code'] != 1){
                        $this->error("成功发布了".$k."条关键词,其他:".$resa['msg']."。",$jupurl);
                    }
                    $datas =[];
                    $datas[$k] =[
                        'user_id'  =>  $userid,
                        'task_name' =>  $task_name ? $task_name : $val["keys"].'-'.date("m/d"),
                        'task_id'  => $taskid,
                        'task_key'  =>  $val["keys"],
                        'taobao_url'  => $task_url ,
                        'taobao_id'  =>  $taobaoid,
                        'task_tpe'  =>  $tpe,
                        'task_num'  =>  $val["num"],
                        'total_num'  =>  $val["num"]*$days,
                        'task_price'  => $price ,
                        'task_money'  => $task_money,
                        'task_day'  =>  $days,
                        'hourCounts'  => $hours,
                        'task_c'  =>  $task_c,
                        'task_fs'  =>  $isfs,
                        'goodstime'  => $goodstime,
                        'etime'  => $etimes
                    ];
                    $logid = $task->saveAll($datas);
                    $usermoney = $usermoney-$task_money;
                    \app\common\model\User::score(0,$jians,$userid,'购买'.$typename.':'.$logid[$k]['id']);
                }//循环结束
                $this->success("发布成功".($k+1)."个关键词",$jupurl);
            }else{
                $this->error("关键词或者数量不能为空！");
            }
		}
	}
    /**
     * 抖音下单检测
     */
    public function dycheck()
    {
        if ($this->request->isPost()) {
            $task_name = $this->request->post('task_name');
            $task_url = $_POST['task_url'];
            $task_num = $this->request->post('nums');
            $days = $this->request->post('days');
            $isfs = $this->request->post('isfs');
            $tpe = $this->request->post('tpe');//任务类型
            switch($tpe){
                case 12:
                    $apitype =12;
                    $typename = '抖音粉丝';
                    $userprice = getlv($this->auth->level)['dyfs'];//用户的单价
                    $jupurl = '/task/dylist.html?task_tpe=12';
                    break;
                case 13:
                    $apitype =13;
                    $typename = '抖音点赞';
                    $userprice = getlv($this->auth->level)['dyfs'];//用户的单价
                    $jupurl = '/task/dylist.html?task_tpe=13';
                    break;
                case 14:
                    $apitype =14;
                    $typename = '抖音评论';
                    $userprice = getlv($this->auth->level)['dyfs'];//用户的单价
                    $jupurl = '/task/dylist.html?task_tpe=14';
                    break;
                case 15:
                    $apitype =15;
                    $typename = '抖音播放';
                    $userprice = getlv($this->auth->level)['dybf'];//用户的单价
                    $jupurl = '/task/dylist.html?task_tpe=15';
                    break;
                case 16:
                    $apitype =16;
                    $typename = '抖音分享';
                    $userprice = getlv($this->auth->level)['dyfx'];//用户的单价
                    $jupurl = '/task/dylist.html?task_tpe=16';
                    break;
                default:
                    $apitype =12;
                    $typename = '抖音粉丝';
                    $userprice = getlv($this->auth->level)['dyfs'];//用户的单价
                    $jupurl = '/task/dylist.html?task_tpe=12';
                    break;
            }
            if($isfs == 'day' && date('H') > 18){$this->error('已经过了18点，无法发布当前方式，请选择其他完成方式');}
            if($days ==1){
                $etimes = strtotime(date("Y/m/d 23:59:59",time()));
            }else{
                $etimes = time()+60*60*24*$days;
            }
            $userid = $this->auth->id;
            $usermoney =$this->auth->money;//用户余额
            $hours = getTaskHour($task_num,$isfs);//每小时任务量
            $task_money = $userprice*$task_num*$days ;//任务总价
            $jians = 0-$task_money;
            if($usermoney < $task_money){
                $this->error("余额不足!");
            }
            $taobaoid = dyid($task_url);//宝贝ID
            if(!$taobaoid){ $this->error("抖音的连接不正确！");}

            $taskdata = array (
                'token'=> config('yto.token'),
                'task_name' => $task_name,
                'task_url' => $task_url,
                'task_num'=> $task_num,
                'days'=> $days,
                'isfs'=> $isfs,
                'tpe'=> $apitype
            );
            $result = sendRequest(config('yto.apiurl').'/api/task/taskdy',$taskdata, 'POST');
            $resa=json_decode($result,true);
            $taskid = $resa['data']['taskid'];
            if($resa['code'] != 1){
                $this->error($resa['msg'],$jupurl);
            }

            $task = model('Task');
            $data['user_id']=$userid;
            $data['task_name']=$task_name;
            $data['task_id']=$taskid;
            $data['taobao_url']=$task_url;
            $data['taobao_id']=$taobaoid;
            $data['task_tpe']=$tpe;
            $data['task_num']=$task_num;
            $data['total_num']=$task_num*$days;
            $data['task_price']=$userprice;
            $data['task_money']=$task_money;
            $data['task_day']=$days;
            $data['hourCounts']=$hours;
            $data['task_fs']=$isfs;
            $data['etime']=$etimes;
            $task->save($data);
            \app\common\model\User::score(0,$jians,$userid,'购买'.$typename.':'.$task->id);
            $this->success("发布成功",$jupurl);
        }
    }
	 /**
 * 更新状态
 */
    public function puzttims()
    {
        $task = model('Task');
        $timestart = strtotime(date("Y/m/d 00:00:00",time()-24*60*60*1));//昨日时间
        $timeend   = strtotime(date("Y/m/d 23:59:59",time()-24*60*60*1));
        $bannian = time()-15552000;
        $upt = $task->where('task_status',0)->where('etime','between',[$timestart,$timeend])->setField('task_status',1);
        $det = $task->where('etime','<',$bannian)->delete();
        $dellog = Db::table('fa_user_score_log')->where('createtime','<',$bannian)->delete();
        // echo $task->getLastSql();
        if($upt){
            echo 'ok--';
        }else{
            echo 'err--';
        }
        if($det){
            echo 'delok--';
        }else{
            echo 'delerr--';
        }
        if($dellog){
            echo 'logok--';
        }else{
            echo 'logerr--';
        }
    }
    /**
     * 查数量
     */
    public function getnum()
    {
        if($this->request->isAjax()){
            $task = model('Task');
            $userid = $this->auth->id;
            $tid = $this->request->post('tid');
            $findsql = $task->where('id',$tid)->where('user_id',$userid)->find();
            if(!$findsql){
                $this->error('未找到记录',null,102);
            }
            $taskdata = array (
                'token'=> config('yto.token'),
                'tid'=> $findsql['task_id']
            );
            $result = sendRequest(config('yto.apiurl').'/api/task/gettask',$taskdata, 'POST');
            $resa=json_decode($result,true);
            $task_finish = $resa['data']['task_finish'];//完成量
            if($resa['code'] != 1){
                $this->error($resa['msg']);
            }else{
                if($task_finish == $findsql['total_num'] && $findsql['task_status'] == 0){
                    $task->where('id',$tid)->where('user_id',$userid)->update(['task_status' => 1,'task_day_finish'=>$task_finish]);
                }else{
                    $task->where('id',$tid)->where('user_id',$userid)->setField('task_day_finish',$task_finish);
                }
                $this->success($task_finish);
            }
        }

    }
    /**
     * 结束任务
     */
    public function end($ids=null)
    {
        $task = model('Task');
        $userid = $this->auth->id;
        $findsql = $task->where('id',$ids)->where('user_id',$userid)->where('task_status',0)->find();
        if(!$findsql){
            $this->error('任务不存在或者任务已经完成');
        }
        $taskid =$findsql['task_id'];//任务ID
        $total_num =$findsql['total_num'];//任务数量
        $price = $findsql['task_price'];//任务单价
        $totalp = $findsql['task_money'];//总价
        //结束
        $taskdata = array (
            'token'=> config('yto.token'),
            'tid'=> $taskid
        );
        $result = sendRequest(config('yto.apiurl').'/api/task/taskend',$taskdata, 'POST');
        $resa=json_decode($result,true);
        $endnum = $resa['data']['task_num'];//剩余量
        $tosum = $total_num -$endnum;//完成量
        $endtotalp = $price*$endnum;//退回总额
        if($resa['code'] == 1){
            $task->where('id',$ids)->where('user_id',$userid)->update(['task_status' => 1,'total_num'=>$tosum,'task_money'=>$totalp - $endtotalp]);
            \app\common\model\User::score(0,$endtotalp,$userid,'取消任务【'.$ids.'】,未完成数量:'.$endnum.',退回'.$endtotalp.'元。');
            $this->success('取消成功');
        }else{
            $this->error('结束失败:'.$resa['msg']);
        }
    }
    /**
     * 淘宝列表
     */
   public function tblist()
    {
        $ids = $this->request->request("task_tpe");
        if($ids==''){
             $ids = 88;
        }
		//设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = model('Task')
                ->where($where)
                ->order($sort, $order)
                ->count();
            $list = model('Task')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
		$this->assign('title', '淘宝任务记录');
        $this->assign('ids', $ids);
        return $this->view->fetch();
    }
    public function jdlist()
    {
        $ids = $this->request->request("task_tpe");
        if($ids==''){
            $ids = 88;
        }
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = model('Task')
                ->where($where)
                ->order($sort, $order)
                ->count();
            $list = model('Task')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        $this->assign('title', '京东任务记录');
        $this->assign('ids', $ids);
        return $this->view->fetch();
    }
    public function pddlist()
    {
        $ids = $this->request->request("task_tpe");
        if($ids==''){
            $ids = 88;
        }
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = model('Task')
                ->where($where)
                ->order($sort, $order)
                ->count();
            $list = model('Task')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        $this->assign('title', '拼多多任务记录');
        $this->assign('ids', $ids);
        return $this->view->fetch();
    }
    public function dylist()
    {
        $ids = $this->request->request("task_tpe");
        if($ids==''){
            $ids = 88;
        }
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = model('Task')
                ->where($where)
                ->order($sort, $order)
                ->count();
            $list = model('Task')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        $this->assign('title', '抖音任务记录');
        $this->assign('ids', $ids);
        return $this->view->fetch();
    }
}
