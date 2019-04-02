<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Config;
use fast\Http;

/**
 * 控制台
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend
{

    /**
     * 查看
     */
    public function index()
    {
        $seventtime = \fast\Date::unixtime('day');
        $paylist = [];
        for ($i = 0; $i < 7; $i++)
        {
			$stime = \fast\Date::unixtime('day', -1*$i);
			$etime = \fast\Date::unixtime('day', -1*$i)+86399;
			$day = date("Y-m-d", $seventtime - ($i * 86400));
			 $where['create_time']=array('between',''.$stime.','.$etime.'');
            $taskcounts = \app\common\model\Task::where($where)->count();//任务数
            $taskmoney = \app\common\model\Task::where($where)->sum('task_money');//任务总额
            $counts = \app\common\model\Express::where($where)->count();//每天单数
            $countsum = \app\common\model\Express::where($where)->sum('price');//每天金额
            $countavg = \app\common\model\Express::where($where)->avg('price');//每天平均
            $paylist[] = array("days " => $day, "taskcount"=>$taskcounts, "taskmoney"=>$taskmoney,"counts " => $counts, "countsum"=>$countsum, "countavg"=>round($countavg,2));
        }
		$moenysum = \app\common\model\Express::sum('price');//总消耗金额
		$usermoenysum = \app\common\model\User::sum('money');//总可用余额
		$usercount = \app\common\model\User::count();//会员数
		//print_r($paylist);

        $data = array('token'=> config('yto.token'));
        $req =Http::post(config('yto.apiurl').'/api/task/getmoney', $data);
        $result=json_decode($req,true);

        $regong =Http::post(config('yto.apiurl').'/api/express/getgg');
        $resultgong=json_decode($regong,true);

        $hooks = config('addons.hooks');
        $uploadmode = isset($hooks['upload_config_init']) && $hooks['upload_config_init'] ? implode(',', $hooks['upload_config_init']) : 'local';
        $addonComposerCfg = ROOT_PATH . '/vendor/karsonzhang/fastadmin-addons/composer.json';
        Config::parse($addonComposerCfg, "json", "composer");
        $config = Config::get("composer");
        $addonVersion = isset($config['version']) ? $config['version'] : __('Unknown');
        $this->view->assign([
            'totaluser'        => empty($result['data']['usermoney'])?$result['msg']:$result['data']['usermoney'],
            'totalviews'       => $usermoenysum,
            'totalorder'       => $moenysum,
            'totalorderamount' => $usercount,
            'todayuserlogin'   => 321,
            'todayusersignup'  => 430,
            'todayorder'       => 2324,
            'unsettleorder'    => 132,
            'gonggao'         => $resultgong['data']['info'],
            'sevendau'         => '32%',
            'paylist'          => $paylist,
            'addonversion'       => $addonVersion,
            'uploadmode'       => $uploadmode
        ]);

        return $this->view->fetch();
    }

}
