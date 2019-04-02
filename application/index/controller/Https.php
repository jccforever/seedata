<?php

namespace app\index\controller;
use think\Controller;
use think\Db;
class Https
{
    /**
     * https 发起post多发请求
     * 
     * @param array $nodes url和参数信息。$nodes = array
     *                                              (
     *                                                 [0] = > array
     *                                                   (
     *                                                       'url' => 'http://www.baidu.com',
     *                                                       'data' => '{"a":1,"b":2}'
     *                                                   ),
     *                                                 [1] = > array
     *                                                   (
     *                                                       'url' => 'http://www.baidu.com',
     *                                                       'data' => null
     *                                                   )
     *                                                 ....
     *                                              )
     * @param int $timeOut 超时设置
     * @return array  
     */
    public static function postMulti($nodes,$timeOut = 5)
    {
        try 
        {
            if (false == is_array($nodes)) 
            {
                return array();
            }
 
            $mh = curl_multi_init(); 
            $curlArray = array();
            foreach($nodes as $key => $info)
            {
                if(false == is_array($info))
                {
                    continue;
                }
                if(false == isset($info['url']))
                {
                    continue;
                }
 
                $ch = curl_init();
                // 设置url
                $url = $info['url'];
                curl_setopt($ch, CURLOPT_URL, $url);
 
                $data = isset($info['data']) ? $info['data'] :null;
                if(false == empty($data))
                {
                    curl_setopt($ch, CURLOPT_POST, 1); 
                    // array
                    if (is_array($data) && count($data) > 0) 
                    {
                        curl_setopt($ch, CURLOPT_POST, count($data));                
                    }
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                }
 
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                // 如果成功只将结果返回，不自动输出返回的内容
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                // user-agent
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:22.0) Gecko/20100101 Firefox/22.0");
                // 超时
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeOut);
                
                $curlArray[$key] = $ch;
                curl_multi_add_handle($mh, $curlArray[$key]); 
            }
 
            $running = NULL; 
            do { 
                usleep(10000); 
                curl_multi_exec($mh,$running); 
            } while($running > 0); 
 
            $res = array(); 
            foreach($nodes as $key => $info) 
            { 
                $res[$key] = curl_multi_getcontent($curlArray[$key]); 
            } 
            foreach($nodes as $key => $info){ 
                curl_multi_remove_handle($mh, $curlArray[$key]); 
            } 
            curl_multi_close($mh);     
            return $res;
        } 
        catch ( Exception $e ) 
        {
            return array();
        }
 
        return array();
    }
 
}
?>