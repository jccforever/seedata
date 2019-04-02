<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Task extends Backend
{
    
    /**
     * Task模型对象
     * @var \app\admin\model\Task
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Task;
        $this->view->assign("taskStatusList", $this->model->getTaskStatusList());
        $this->view->assign("taskTpeList", $this->model->getTaskTpeList());
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->with(['user'])
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['user'])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                
                $row->getRelation('user')->visible(['username']);
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
    /**
     * 查数量
     */
    public function admingetnum()
    {
        if($this->request->isAjax()){
            $task = model('Task');
            $tid = $this->request->post('tid');
            $findsql = $task->where('id',$tid)->find();
            if(!$findsql){
                $this->error('未找到记录',null,102);
            }
            $userid = $findsql['user_id'];
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
    public function adminend($ids=null)
    {
        $task = model('Task');
        $findsql = $task->where('id',$ids)->where('task_status',0)->find();
        if(!$findsql){
            $this->error('任务不存在或者任务已经完成');
        }
        $userid = $findsql['user_id'];
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
}
