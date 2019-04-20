<?php
// 公共助手函数
use think\Db;
if (!function_exists('__')) {

    /**
     * 获取语言变量值
     * @param string $name 语言变量名
     * @param array $vars 动态变量值
     * @param string $lang 语言
     * @return mixed
     */
    function __($name, $vars = [], $lang = '')
    {
        if (is_numeric($name) || !$name)
            return $name;
        if (!is_array($vars)) {
            $vars = func_get_args();
            array_shift($vars);
            $lang = '';
        }
        return \think\Lang::get($name, $vars, $lang);
    }

}

if (!function_exists('format_bytes')) {

    /**
     * 将字节转换为可读文本
     * @param int $size 大小
     * @param string $delimiter 分隔符
     * @return string
     */
    function format_bytes($size, $delimiter = '')
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 6; $i++)
            $size /= 1024;
        return round($size, 2) . $delimiter . $units[$i];
    }

}

if (!function_exists('datetime')) {

    /**
     * 将时间戳转换为日期时间
     * @param int $time 时间戳
     * @param string $format 日期时间格式
     * @return string
     */
    function datetime($time, $format = 'Y-m-d H:i:s')
    {
        $time = is_numeric($time) ? $time : strtotime($time);
        return date($format, $time);
    }

}

if (!function_exists('human_date')) {

    /**
     * 获取语义化时间
     * @param int $time 时间
     * @param int $local 本地时间
     * @return string
     */
    function human_date($time, $local = null)
    {
        return \fast\Date::human($time, $local);
    }

}

if (!function_exists('cdnurl')) {

    /**
     * 获取上传资源的CDN的地址
     * @param string $url 资源相对地址
     * @param boolean $domain 是否显示域名 或者直接传入域名
     * @return string
     */
    function cdnurl($url, $domain = false)
    {
        $url = preg_match("/^https?:\/\/(.*)/i", $url) ? $url : \think\Config::get('upload.cdnurl') . $url;
        if ($domain && !preg_match("/^(http:\/\/|https:\/\/)/i", $url)) {
            if (is_bool($domain)) {
                $public = \think\Config::get('view_replace_str.__PUBLIC__');
                $url = rtrim($public, '/') . $url;
                if (!preg_match("/^(http:\/\/|https:\/\/)/i", $url)) {
                    $url = request()->domain() . $url;
                }
            } else {
                $url = $domain . $url;
            }
        }
        return $url;
    }

}


if (!function_exists('is_really_writable')) {

    /**
     * 判断文件或文件夹是否可写
     * @param    string $file 文件或目录
     * @return    bool
     */
    function is_really_writable($file)
    {
        if (DIRECTORY_SEPARATOR === '/') {
            return is_writable($file);
        }
        if (is_dir($file)) {
            $file = rtrim($file, '/') . '/' . md5(mt_rand());
            if (($fp = @fopen($file, 'ab')) === FALSE) {
                return FALSE;
            }
            fclose($fp);
            @chmod($file, 0777);
            @unlink($file);
            return TRUE;
        } elseif (!is_file($file) OR ($fp = @fopen($file, 'ab')) === FALSE) {
            return FALSE;
        }
        fclose($fp);
        return TRUE;
    }

}

if (!function_exists('rmdirs')) {

    /**
     * 删除文件夹
     * @param string $dirname 目录
     * @param bool $withself 是否删除自身
     * @return boolean
     */
    function rmdirs($dirname, $withself = true)
    {
        if (!is_dir($dirname))
            return false;
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dirname, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
        if ($withself) {
            @rmdir($dirname);
        }
        return true;
    }

}

if (!function_exists('copydirs')) {

    /**
     * 复制文件夹
     * @param string $source 源文件夹
     * @param string $dest 目标文件夹
     */
    function copydirs($source, $dest)
    {
        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        }
        foreach (
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if ($item->isDir()) {
                $sontDir = $dest . DS . $iterator->getSubPathName();
                if (!is_dir($sontDir)) {
                    mkdir($sontDir, 0755, true);
                }
            } else {
                copy($item, $dest . DS . $iterator->getSubPathName());
            }
        }
    }

}

if (!function_exists('mb_ucfirst')) {

    function mb_ucfirst($string)
    {
        return mb_strtoupper(mb_substr($string, 0, 1)) . mb_strtolower(mb_substr($string, 1));
    }

}

if (!function_exists('addtion')) {

    /**
     * 附加关联字段数据
     * @param array $items 数据列表
     * @param mixed $fields 渲染的来源字段
     * @return array
     */
    function addtion($items, $fields)
    {
        if (!$items || !$fields)
            return $items;
        $fieldsArr = [];
        if (!is_array($fields)) {
            $arr = explode(',', $fields);
            foreach ($arr as $k => $v) {
                $fieldsArr[$v] = ['field' => $v];
            }
        } else {
            foreach ($fields as $k => $v) {
                if (is_array($v)) {
                    $v['field'] = isset($v['field']) ? $v['field'] : $k;
                } else {
                    $v = ['field' => $v];
                }
                $fieldsArr[$v['field']] = $v;
            }
        }
        foreach ($fieldsArr as $k => &$v) {
            $v = is_array($v) ? $v : ['field' => $v];
            $v['display'] = isset($v['display']) ? $v['display'] : str_replace(['_ids', '_id'], ['_names', '_name'], $v['field']);
            $v['primary'] = isset($v['primary']) ? $v['primary'] : '';
            $v['column'] = isset($v['column']) ? $v['column'] : 'name';
            $v['model'] = isset($v['model']) ? $v['model'] : '';
            $v['table'] = isset($v['table']) ? $v['table'] : '';
            $v['name'] = isset($v['name']) ? $v['name'] : str_replace(['_ids', '_id'], '', $v['field']);
        }
        unset($v);
        $ids = [];
        $fields = array_keys($fieldsArr);
        foreach ($items as $k => $v) {
            foreach ($fields as $m => $n) {
                if (isset($v[$n])) {
                    $ids[$n] = array_merge(isset($ids[$n]) && is_array($ids[$n]) ? $ids[$n] : [], explode(',', $v[$n]));
                }
            }
        }
        $result = [];
        foreach ($fieldsArr as $k => $v) {
            if ($v['model']) {
                $model = new $v['model'];
            } else {
                $model = $v['name'] ? \think\Db::name($v['name']) : \think\Db::table($v['table']);
            }
            $primary = $v['primary'] ? $v['primary'] : $model->getPk();
            $result[$v['field']] = $model->where($primary, 'in', $ids[$v['field']])->column("{$primary},{$v['column']}");
        }

        foreach ($items as $k => &$v) {
            foreach ($fields as $m => $n) {
                if (isset($v[$n])) {
                    $curr = array_flip(explode(',', $v[$n]));

                    $v[$fieldsArr[$n]['display']] = implode(',', array_intersect_key($result[$n], $curr));
                }
            }
        }
        return $items;
    }

}

if (!function_exists('var_export_short')) {

    /**
     * 返回打印数组结构
     * @param string $var 数组
     * @param string $indent 缩进字符
     * @return string
     */
    function var_export_short($var, $indent = "")
    {
        switch (gettype($var)) {
            case "string":
                return '"' . addcslashes($var, "\\\$\"\r\n\t\v\f") . '"';
            case "array":
                $indexed = array_keys($var) === range(0, count($var) - 1);
                $r = [];
                foreach ($var as $key => $value) {
                    $r[] = "$indent    "
                        . ($indexed ? "" : var_export_short($key) . " => ")
                        . var_export_short($value, "$indent    ");
                }
                return "[\n" . implode(",\n", $r) . "\n" . $indent . "]";
            case "boolean":
                return $var ? "TRUE" : "FALSE";
            default:
                return var_export($var, TRUE);
        }
    }

}
if (!function_exists('replaceSpecialChar')) {

    /**
     * 过滤字符
     * @param string $var 数组
     * @param string $indent 缩进字符
     * @return string
     */
		 function replaceSpecialChar($strParam){
			 $regex = "/\ |\/|\~|\!|\@|\#|\\$|\%|\"|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\=|\\\|\|/";
			 return preg_replace($regex,"",$strParam);
		}

}
if(!function_exists('get_paynum')){
    /**
     * 获取充值次数
     * @Author   zsw
     * @DataTime 2018-12-07T15:56:05+0800
     * @param    [type]                   $user_id [description]
     * @return   [type]                            [description]
     */
    function get_paynum($user_id) {
        $pay_num = \think\Db::name('user_score_log')->where(['user_id'=>$user_id,'before'=>1])->count();
        // $pay_num = count($pay_num);
        return $pay_num;
    }
}
if(!function_exists('taiSign')){
    /**
     * 圆通太阳签名
     *
     * @param string $path 指定的path
     * @return string
     */
    function taiSign($params,$appSecret)
    {

        ksort($params);

        $stringToBeSigned = $appSecret;
        foreach ($params as $k => $v)
        {
            if(is_string($v) && "@" != substr($v, 0, 1))
            {
                $stringToBeSigned .= "$k$v";
            }
        }
        unset($k, $v);
        $stringToBeSigned .= $appSecret;

        $sign= strtoupper(md5($stringToBeSigned));


        return $sign;
    }
}
if(!function_exists('sendRequest')){
    /**
     * CURL发送Request请求,含POST和REQUEST
     * @param string $url 请求的链接
     * @param mixed $params 传递的参数
     * @param string $method 请求的方法
     * @param mixed $options CURL的参数
     * @return array
     */
    function sendRequest($url, $params = [], $method = 'POST', $options = [])
    {
        $method = strtoupper($method);
        $protocol = substr($url, 0, 5);
        $query_string = is_array($params) ? http_build_query($params) : $params;

        $ch = curl_init();
        $defaults = [];
        if ('GET' == $method)
        {
            $geturl = $query_string ? $url . (stripos($url, "?") !== FALSE ? "&" : "?") . $query_string : $url;
            $defaults[CURLOPT_URL] = $geturl;
        }
        else
        {
            $defaults[CURLOPT_URL] = $url;
            if ($method == 'POST')
            {
                $defaults[CURLOPT_POST] = 1;
            }
            else
            {
                $defaults[CURLOPT_CUSTOMREQUEST] = $method;
            }
            $defaults[CURLOPT_POSTFIELDS] = $query_string;
        }

        $defaults[CURLOPT_HEADER] = FALSE;
        $defaults[CURLOPT_USERAGENT] = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.98 Safari/537.36";
        $defaults[CURLOPT_FOLLOWLOCATION] = TRUE;
        $defaults[CURLOPT_RETURNTRANSFER] = TRUE;
        $defaults[CURLOPT_CONNECTTIMEOUT] = 20;
        $defaults[CURLOPT_TIMEOUT] = 20;

        // disable 100-continue
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));

        if ('https' == $protocol)
        {
            $defaults[CURLOPT_SSL_VERIFYPEER] = FALSE;
            $defaults[CURLOPT_SSL_VERIFYHOST] = FALSE;
        }

        curl_setopt_array($ch, (array) $options + $defaults);

        $ret = curl_exec($ch);
        $err = curl_error($ch);

        if (FALSE === $ret || !empty($err))
        {
            $errno = curl_errno($ch);
            $info = curl_getinfo($ch);
            curl_close($ch);
            return [
                'ret'   => FALSE,
                'errno' => $errno,
                'msg'   => $err,
                'info'  => $info,
            ];
        }
        curl_close($ch);
        return $ret;
    }
}
if(!function_exists('getlv')){
    /**
     * 获取充值次数
     * @Author   zsw
     * @DataTime 2018-12-07T15:56:05+0800
     * @param    [type]                   $user_id [description]
     * @return   [type]                            [description]
     */
    function getlv($lvid = 1){
        $lvinfo = Db::name('user_level')->where('id', $lvid)->find();
        return $lvinfo;
    }
}
if(!function_exists('tbid')){
    /**
     * 获取淘宝ID
     * @Author   zsw
     * @DataTime 2018-12-07T15:56:05+0800
     * @param    [type]                   $user_id [description]
     * @return   [type]                            [description]
     */
    function tbid($strurl) {
        $id = '';
        $arr = parse_url($strurl);
        $arr_query = $arr['query'];
        parse_str($arr_query,$data);
        return $data['id'];
    }
}
if(!function_exists('tmid')){
    /**
     * 获取天猫id
     */
    function tmid($url){
        $urlArr = parse_url($url);
        $query = $urlArr['query'];
        parse_str($query,$arr);
        return $arr['id'];
    }
}
if(!function_exists('jdid')){
    /**
     * 获取淘京东ID
     * @Author   zsw
     * @DataTime 2018-12-07T15:56:05+0800
     * @param    [type]                   $user_id [description]
     * @return   [type]                            [description]
     */
    function jdid($strurl) {
        $start = 'com/';
        $end = '.h';
        $substr = substr($strurl, strlen($start)+strpos($strurl, $start),(strlen($strurl) - strpos($strurl, $end))*(-1));
        return $substr;
    }
}

if(!function_exists('strbu')){
    /**
     * 获取淘京东ID
     * @Author   zsw
     * @DataTime 2018-12-07T15:56:05+0800
     * @param    [type]                   $user_id [description]
     * @return   [type]                            [description]
     */
    function strbu($strurl,$st,$end) {
        $start = $st;
        $end = $end;
        $substr = substr($strurl, strlen($start)+strpos($strurl, $start),(strlen($strurl) - strpos($strurl, $end))*(-1));
        return $substr;
    }
}

if(!function_exists('pddid')){
    /**
     * 获取拼多多ID
     * @Author   zsw
     * @DataTime 2018-12-07T15:56:05+0800
     * @param    [type]                   $user_id [description]
     * @return   [type]                            [description]
     */
    function pddid($strurl) {
        $goods_id = '';
        $arr = parse_url($strurl);
        $arr_query = $arr['query'];
        parse_str($arr_query);
        return $goods_id;
    }
}
if(!function_exists('dyid')){
    /**
     * 获取抖音ID
     * @Author   zsw
     * @DataTime 2018-12-07T15:56:05+0800
     * @param    [type]                   $user_id [description]
     * @return   [type]                            [description]
     */
    function dyid($strurl) {
        $start = 'video/';
        $end = '/?';
        $substr = substr($strurl, strlen($start)+strpos($strurl, $start),(strlen($strurl) - strpos($strurl, $end))*(-1));
        return $substr;
    }
}
if(!function_exists('getTaskHour')){
    /**
     * 获取任务时间方法
     * @author: Kai <maifakai@jjweb.net>
     * @param int $count 任务数量
     * @param string $type 任务类型,clear:全部清空/today:当天全部完成/day:白天分配/curve:曲线分配
     * @param array $weightHour
     * @return array
     */
    function getTaskHour($count, $type, $weightHour = ''){
        date_default_timezone_set('PRC');
        $weightHour = !empty($weightHour) ? $weightHour : array(5,4,2,1,2,3,4,5,6,7,8,9,10,11,12,13,12,11,10,9,8,7,6,5);
        $list = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        list($used, $start, $end) = array(0, 0, 23);
        switch($type){
            case 'clear':
                //清空
                return $list;
                break;
            case 'today':
                //当天全部完成
                $start = date('i') > 40 ? date('H') + 1 : date('H');
                break;
            case 'day':
                //白天分配
                $start = date('H');
                $end = 18;
                break;
            case 'curve':
                //曲线分配
                $start = date('H');
                $end = date('H')+1;
                break;
            default:
                //当天全部完成
                $start = date('i') > 40 ? date('H') + 1 : date('H');
                break;
        }
        //function getWeight
        $a = 0;
        foreach ($weightHour as $key=>$val){
            if($key >= $start && $key <= $end){
                $a += $val;
            }
        }

        $average = $count / round($a, 13);
        $averageArr = array();
        foreach ($list as $index=>$item){
            if($index < 24 && $index >= $start && $index <= $end){
                $int = floor($weightHour[$index] * $average);
                if($used < $count){
                    $int = $int > $count - $used ? $count - $used : $int;
                    $list[$index] = $int;
                    $used += $int;
                };
                $averageArr[] = array(
                    'index' => $index,
                    'value' => $weightHour[$index]
                );
            }else{
                $list[$index] = 0;
            }
        }
        $duoyu = $count - $used;
        if($duoyu > 0){

            //function arraySequence
            $arrSort = array();
            foreach ($averageArr as $uniqid => $row) {
                foreach ($row as $key => $value) {
                    $arrSort[$key][$uniqid] = $value;
                }
            }
            array_multisort($arrSort['value'], constant('SORT_DESC'), $averageArr);

            $i = 0;
            while($i < $duoyu){
                if(!!$averageArr[$i]){
                    $list[$averageArr[$i]['index']] += 1;
                }
                $i++;
            };
        }
        return implode(',', $list);
    }
}
if(!function_exists('timediff')){
    function timediff($begin_time,$end_time){
        if($begin_time < $end_time){
            $starttime = $begin_time;
            $endtime = $end_time;
        }else{
            $starttime = $end_time;
            $endtime = $begin_time;
        }
        //计算天数
        $timediff = $endtime-$starttime;
        $days = intval($timediff/86400);
        //计算小时数
        $remain = $timediff%86400;
        $hours = intval($remain/3600);
        //计算分钟数
        $remain = $remain%3600;
        $mins = intval($remain/60);
        //计算秒数
        $secs = $remain%60;
        $res = array("day" => $days,"hour" => $hours,"min" => $mins,"sec" => $secs);
        return $res;
    }
}
if(!function_exists('get_user_access')){
    function get_user_access($level){
        $info = Db::name('user_level')->where('id',$level)->find();
        return $info;
    }
}
//会员权限
if(!function_exists('get_right_control')){
    function get_right_control($level,$exists,$upper_limit,$common_upper_limit,$time,$expire_time){
        if($level==2){
            if($exists>=$upper_limit){
                return $is_add = 0;
            }else{
                return $is_add = 1;
            }
        }
        if($level==3 || $level==4){
            if($expire_time-$time<10){
                if($exists>=$common_upper_limit){
                    return ['is_add'=>0,'is_expire_time'=>1];
                }else{
                    return $is_add = 1;
                }
            }else{
                if($exists>=$upper_limit){
                    return $is_add = 0;
                }else{
                    return $is_add = 1;
                }
            }
        }
    }
}
if(!function_exists('update_pusher')){
    function update_pusher($level,$expire_time,$id,$duration,$time){
        if($level==2){
            $update = [
                'id'=>$id,
                'level'=>3,
                'expire_time'=>strtotime($duration)
            ];
        }
        if($level==3){
            $update = is_expire($expire_time,$time,$duration,$id);
        }
        if($level==4){
            $update = is_expire($expire_time,$time,$duration,$id);
        }
        return $update;
    }
}
if(!function_exists('is_expire')){
    function is_expire($expire_time,$time,$duration,$id){
        if($expire_time-$time<10){
            $expire_time = 0;
            $expire_time = $expire_time + strtotime($duration);
            $update = [
                'id'=>$id,
                'expire_time'=>$expire_time
            ];
        }else{
            $expire_time = $expire_time - $time;
            $expire_time = $expire_time + strtotime($duration);
            $update = [
                'id'=>$id,
                'expire_time'=>$expire_time
            ];
        }
        return $update;
    }
}
