<?php

namespace app\admin\model;

use think\Model;

class Task extends Model
{
    // 表名
    protected $name = 'task';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'goodstime_text',
        'task_status_text',
        'task_tpe_text',
        'etime_text',
        'create_time_text'
    ];
    

    
    public function getTaskStatusList()
    {
        return ['0' => __('Task_status 0'),'1' => __('Task_status 1')];
    }     

    public function getTaskTpeList()
    {
        return ['1' => __('Task_tpe 1'),'2' => __('Task_tpe 2'),'3' => __('Task_tpe 3'),'4' => __('Task_tpe 4'),'5' => __('Task_tpe 5'),'6' => __('Task_tpe 6'),'7' => __('Task_tpe 7'),'8' => __('Task_tpe 8'),'9' => __('Task_tpe 9'),'10' => __('Task_tpe 10'),'11' => __('Task_tpe 11'),'12' => __('Task_tpe 12'),'13' => __('Task_tpe 13'),'14' => __('Task_tpe 14'),'15' => __('Task_tpe 15'),'16' => __('Task_tpe 16')];
    }     


    public function getGoodstimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['goodstime']) ? $data['goodstime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getTaskStatusTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['task_status']) ? $data['task_status'] : '');
        $list = $this->getTaskStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getTaskTpeTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['task_tpe']) ? $data['task_tpe'] : '');
        $list = $this->getTaskTpeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getEtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['etime']) ? $data['etime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getCreateTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['create_time']) ? $data['create_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setGoodstimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    protected function setEtimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    protected function setCreateTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }


    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
