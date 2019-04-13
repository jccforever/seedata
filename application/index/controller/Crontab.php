<?php
namespace app\index\controller;
use \think\Controller;
use think\Config;
use think\Cookie;
use think\Hook;
use think\Session;
use think\Validate;
use think\Db;
use think\Request;
/**
 * 任务管理
 */
class Crontab extends Controller
{
    /**
     * 淘大象接口
     */
    public function DxApi($keywords,$goods_id,$startpage=1)
    {
        $appkey = '12372315';
        $secretkey = '37B3F22CEF0141D9DF7243C92776608D';
        //组装参数
        $paramArr = array(
            'app_key'=>$appkey,
            'method'=>'dx.rank.get',
            'format'=>'json',
            'sign_method'=>'md5',
            'v'=>'1.0',
            'timestamp'=>date('Y-m-d H:i:s'),
            'media'=>'1',
            'pattern'=>'1',
            'sort'=>'0',
            'q'=>$keywords,
            'goodid'=>$goods_id,
            'startpage'=>$startpage,
            'pagestep'=>2
        );
        //生成签名
        $sign = $this->createSign($paramArr);
        //组织参数
        $strParam = $this->createStrParam($paramArr);
        $strParam .='sign='.$sign;
        //请求淘大象接口
        $url = 'https://www.taodaxiang.com/api/router/?'.$strParam;
        $data = sendRequest($url);
        return $data;
    }
    /**
     * 生成签名
     */
    public function createSign($paramArr)
    {
        $sign = '37B3F22CEF0141D9DF7243C92776608D';
        $str = '';
        ksort($paramArr);
        foreach ($paramArr as $key => $val) {
            if ($key != '' && $val != '') {
                $str .= $key.$val;
            }
        }
        $sign = $sign.$str.$sign;
        $sign = strtoupper(md5($sign));
        return $sign;
    }
    /**
     * 组装参数
     */
    public function createStrParam($paramArr)
    {
        $strParam = "";
        foreach($paramArr as $key=>$val){
            if($key !=''&& $val !=''){
                $strParam .=$key.'='.urlencode($val).'&';
            }
        }
        return $strParam;
    }
    /**
     * 京东接口
     */
    public function jdApi($keywords,$link_id)
    {
        $keywords = preg_replace('# #','',$keywords);
        for($i=1;$i<=3;$i++){
            $jdApi = "https://so.m.jd.com/ware/search._m2wq_list?keyword=".$keywords."&page=".$i."&pagesize=100";
            $str = sendRequest($jdApi,$params=[],'GET');
            $str = strbu($str,'searchCB(','})');
            $str = $str."}";
            $str = stripslashes($str);
            $arr = json_decode($str,1);
            $data = $arr['data']['searchm']['Paragraph'];
            if(!is_array($data)){
                return $return=[];
                continue;
            }
            $count =count($data);
            $return = [];
            for($j=0;$j<$count;$j++){
                if($data[$j]['wareid']==$link_id){
                    $return = $data[$j];
                    $return['page']=floor((($i-1)*100+$j)/10)?:1;
                    $return['pos']=(($i-1)*100+$j+1)%10?:10;
                    break;
                }
            }
            if(!empty($return)){
                break;
            }
        }
        return $return;
    }
    /**
     * 获取京东商品销量
     */
    public function jd_sales($goods_id)
    {
        //公共参数
        $serverUrl = 'https://api.jd.com/routerjson';//url
        $access_token = '3df0d2f9-5688-4590-aa64-e6cc73fbca56';
        $app_key = '8989B5297BF7486182D2FF38994A4FA4';
        $app_secret = '84bb90c4617a437e91b96310452e7c3d';
        $vesion = '2.0';
        $format = 'json';
        $method = 'jingdong.service.promotion.goodsInfo';
        $timestamp = date('Y-m-d H:i:s');
        $skuIds = $goods_id;
        $skuIds = [
            'skuIds'=>$skuIds
        ];
        $params = array(
            'method'=>$method,
            'access_token'=>$access_token,
            'app_key'=>$app_key,
            'timestamp'=>$timestamp,
            'format' =>$format,
            'v'=>$vesion,
            '360buy_param_json'=>json_encode($skuIds)
        );
        //生成签名
        $sign = $this->createJdsign($params);
        //组装参数
        $strParams = $this->createJdStrParam($params);
        $strParams .='sign='.$sign; 
        $url = $serverUrl.'?'.$strParams;
        //请求
        $json = sendRequest($url,$params=[],'GET');
        return $json;
        
    }
    /**
     * 生成京东签名
     */
    public function createJdsign($params)
    {
        ksort($params);
        $access_token = '84bb90c4617a437e91b96310452e7c3d';
        $str = '';
        foreach ($params as $k=>$v){
            if('@' !=substr($v,0,1)){
                $str .= "$k$v";
            }
        }
        $sign = $access_token.$str.$access_token;
        $sign = strtoupper(md5($sign));
        return $sign;
    }
    /**
     * 京东api组装参数
     */
    public function createJdStrParam($paramArr)
    {
        $strParam = "";
        foreach($paramArr as $key=>$val){
            if($key !=''&& $val !=''){
                $strParam .=$key.'='.urlencode($val).'&';
            }
        }
        return $strParam;
    }
    /**
     * 监控排名定时任务
     */
    public function monitor_crontab()
    {
        $timestart = strtotime(date("Y/m/d 00:00:00",time()-24*60*60*1));//昨日开始
        $timeend   = strtotime(date("Y/m/d 23:59:59",time()-24*60*60*1));//昨天结束
        $one = Db::name('links_keywords')
                ->where(['create_time'=>['between',[$timestart,$timeend]]])
                ->find();
        $n = 1;
        while($one){
            if($one['terrace']=='淘宝' || $one['terrace']=='天猫'){
                for($i=1;$i<=13;$i+=2){
                    $json = '';
                    $json = $this->DxApi($one['keywords'],$one['link_id'],$i);
                    if(is_string($json)){
                        $arr = json_decode($json,1);
                        if(array_key_exists('data',$arr)){
                            $items = $arr['data']['items'];
                        } 
                    }
                    if(!empty($items)){
                        break; 
                    }
                }
                if(empty($items)){
                    $n++;
                    //改变时间 改变累计次数
                    if($n==3){
                        Db::name('links_keywords')->where('id',$one['id'])->setField(['try_count'=>$n,'create_time'=>time()]);
                        echo "未查到记录";
                        $one = Db::name('links_keywords')
                                ->where(['create_time'=>['between',[$timestart,$timeend]]])
                                ->find();
                        $n = 1;
                    }
                }else{
                    //处理业务
                    $tbApiUrl = 'https://acs.m.taobao.com/h5/mtop.taobao.detail.getdetail/6.0/?data=%7B%22itemNumId%22%3A%22'.$one['link_id'].'%7D&qq-pf-to=pcqq.group';
                    $tbJson = sendRequest($tbApiUrl,$params=[],'GET');
                    $tbArr = json_decode($tbJson,1);
                    $value = $tbArr['data']['apiStack'][0]['value'];
                    $value = json_decode($value,1);
                    $sale_count = $value['item']['sellCount'];//销量
                    $comment_num = $tbArr['data']['item']['commentCount'];//评价
                    $goods_title = $items[0]['title'];//商品标题
                    $shop_name = $items[0]['nick'];//店铺名称
                    $goods_img = $items[0]['img'];//商品主图
                    $goods_price = $items[0]['price'];//商品价格
                    $page = $items[0]['page'];//页码
                    $position = $items[0]['pos'];//位置
                    $strJson = [];
                    $strJson['page'] = $page;
                    $strJson['pos'] = $position;
                    $strJson = json_encode($strJson);
                    //更新数据
                    $update = [
                        'mobile_update_time'=>time(),
                        'create_time'=>time(),
                        'page'  =>$page,
                        'position'=>$position,
                        'device_type'=>'mobile',
                        'mobile'=>$strJson
                    ];
                    $sql1 = Db::name('links_keywords')->where('id',$one['id'])->update($update);
                    $insert = [
                        'rank_record'=>json_encode($items),
                        'keywords'=>$one['keywords'],
                        'user_id'=>$one['user_id'],
                        'goods_id'=>$one['link_id'],
                        'add_time'=>time(),
                        'update_time'=>time(),
                        'device_type'=>'mobile',
                        'page'=>$page,
                        'position'=>$position,
                        'for_id'=>$one['for_id'],
                        'terrace'=>$one['terrace'],
                        'shop_name'=>$shop_name,
                        'goods_title'=>$goods_title,
                        'goods_img'=>$goods_img,
                        'goods_price'=>$goods_price,
                        'sale_count' =>$sale_count,
                        'remark_count'=>$comment_num,
                        'for_table'=>'monitor'
                    ];
                    $sql2 = Db::name('rank_record')->insert($insert);
                    $sql3 = Db::name('links')->where('id',$one['for_id'])->setField(['shop_name'=>$shop_name,'update_time'=>time()]);
                    $one = Db::name('links_keywords')
                            ->where(['create_time'=>['between',[$timestart,$timeend]]])
                            ->find();
                } 
            }elseif($one['terrace']=='京东'){
                $data = $this->jdApi($one['keywords'],$one['link_id']);
                if(empty($data)){
                    $n++;
                    if($n==3){
                        Db::name('links_keywords')->where('id',$one['id'])->setField(['try_count'=>$n,'create_time'=>time()]);
                        $one = Db::name('links_keywords')
                                ->where(['create_time'=>['between',[$timestart,$timeend]]])
                                ->find();
                        $n = 1;
                        echo "no record";
                    }
                }else{
                    $items = $data;
                    $goods_title = $data['Content']['warename'];//商品标题
                    $shop_name = $data['shop_name'];//店铺名称
                    $goods_img = '//img13.360buyimg.com/n1/s450x450_'.$data['Content']['imageurl'];//商品主图
                    $goods_price = $data['dredisprice'];//商品价格
                    $comment_num = $data['commentcount'];//商品评价数量
                    $page = (int)$data['page'];//页码
                    $position = $data['pos'];//位置
                    $strJson = [];
                    $strJson['page'] = $page;
                    $strJson['pos'] = $position;
                    $strJson = json_encode($strJson);
                    $jdJson = $this->jd_sales($one['link_id']);
                    $jdArr = json_decode($jdJson,1);
                    $jdArray = $jdArr['jingdong_service_promotion_goodsInfo_responce'];
                    $jdArray = $jdArray['getpromotioninfo_result'];
                    $jdRes = json_decode($jdArray,1);
                    $jd_result = $jdRes['result'][0];
                    $sale_count = $jd_result['inOrderCount'];//宝贝销量
                    //更新数据
                    $update = [
                        'mobile_update_time'=>time(),
                        'create_time'=>time(),
                        'page'  =>$page,
                        'position'=>$position,
                        'device_type'=>'mobile',
                        'mobile'=>$strJson
                    ];
                    $sql1 = Db::name('links_keywords')->where('id',$one['id'])->update($update);
                    $insert = [
                        'rank_record'=>json_encode($items),
                        'keywords'=>$one['keywords'],
                        'user_id'=>$one['user_id'],
                        'goods_id'=>$one['link_id'],
                        'add_time'=>time(),
                        'update_time'=>time(),
                        'device_type'=>'mobile',
                        'page'=>$page,
                        'position'=>$position,
                        'for_id'=>$one['for_id'],
                        'terrace'=>$one['terrace'],
                        'shop_name'=>$shop_name,
                        'goods_title'=>$goods_title,
                        'goods_img'=>$goods_img,
                        'goods_price'=>$goods_price,
                        'remark_count'=>$comment_num,
                        'sale_count'=>$sale_count,
                        'for_table'=>'monitor'
                    ];
                    $sql2 = Db::name('rank_record')->insert($insert);
                    $sql3 = Db::name('links')->where('id',$one['for_id'])->setField(['shop_name'=>$shop_name,'update_time'=>time()]);
                    $one = Db::name('links_keywords')
                        ->where(['create_time'=>['between',[$timestart,$timeend]]])
                        ->find();
                }
            }  
        }
    }
    /**
     * 竞品排名定时任务
     */
    public function contend_crontab()
    {
        $timestart = strtotime(date("Y/m/d 00:00:00",time()-24*60*60*1));//昨日开始
        $timeend   = strtotime(date("Y/m/d 23:59:59",time()-24*60*60*1));//昨天结束 
        $one = Db::name('competitor')->where('last_update_time','between',[$timestart,$timeend])->find();
        $n = 1;
        while($one){
            if($one['terrace']=='天猫' || $one['terrace']=='淘宝'){
                $link_id = $one['link_id'];
                $apiUrl = 'https://acs.m.taobao.com/h5/mtop.taobao.detail.getdetail/6.0/?data=%7B%22itemNumId%22%3A%22'.$link_id.'%7D&qq-pf-to=pcqq.group';
                $json = sendRequest($apiUrl,$params=[],'GET');
                $arr = json_decode($json,1);
                if(is_array($arr) && $arr['data']['item']){
                    $goods_id = $arr['data']['item']['itemId'];//宝贝id
                    $goods_title = $arr['data']['item']['title'];//宝贝标题
                    $goods_img = $arr['data']['item']['images'][0];//宝贝主图
                    $mockData = $arr['data']['mockData'];
                    $mockData = json_decode($mockData,1);
                    $goods_price = $mockData['price']['price']['priceText'];//商品价格
                    $value = $arr['data']['apiStack'][0]['value'];
                    $value = json_decode($value,1);
                    $sale_count = $value['item']['sellCount'];//销量
                    $remark_count = $arr['data']['item']['commentCount'];//评价
                    $shop_name = $arr['data']['seller']['shopName'];//店铺名称
                    $rank_record = json_encode(compact('goods_id','goods_title','goods_img','goods_price','sale_count','remark_count','shop_name'));
                    //更新竞品
                    $insert['add_time'] = time();
                    $insert['last_update_time'] = time();
                    $insert['shop_name'] = $shop_name;
                    $sql = Db::name('competitor')->where('id',$one['id'])->update($insert);
                    //保存记录
                    $insert2 = [
                        'for_table'=>'competitor',
                        'rank_record'=>$rank_record,
                        'user_id' =>$one['user_id'],
                        'goods_id'=>$link_id,
                        'goods_title'=>$goods_title,
                        'goods_img' =>$goods_img,
                        'goods_price'=>$goods_price,
                        'sale_count'=>$sale_count,
                        'remark_count'=>$remark_count,
                        'add_time'=>time(),
                        'for_id'=>$one['id'],
                        'terrace'=>$one['terrace'],
                        'shop_name'=>$shop_name,
                        'update_time'=>time()
                    ];
                    $sql2 = Db::name('rank_record')->insert($insert2);
                    $one = Db::name('competitor')->where('last_update_time','between',[$timestart,$timeend])->find();;
                }else{
                    $n++;
                    if($n==3){
                        $sql = Db::name('competitor')->where('id',$one['id'])->setField(['try_count'=>$n,'last_update_time'=>time()]);
                        $one = Db::name('competitor')->where('last_update_time','between',[$timestart,$timeend])->find();
                        $n = 1;
                        echo "未找到该商品,竞品更新失败";
                    }
                }
            }elseif($one['terrace']=='京东'){
                $apiUrl = 'https://re.jd.com/cps/item/'.$one['link_id'].'.html';
                $json = sendRequest($apiUrl,$params=[],'GET');
                $start = 'var pageData';
                $end = '};';
                $json = strbu($json,$start,$end);
                $json = $json."}";
                $json = trim(str_replace(' = ','', $json));
                $arr = json_decode($json,1);
                if(!is_array($arr) || !$arr['detail']['ad_title']){
                    $n++;
                    if($n==3){
                        $sql = Db::name('competitor')->where('id',$one['id'])->setField(['try_count'=>$n,'last_update_time'=>time()]);
                        $one = Db::name('competitor')->where('last_update_time','between',[$timestart,$timeend])->find();
                        $n = 1;
                        echo "未找到该商品,竞品更新失败";
                    }
                }
                $jdJson = $this->jd_sales($one['link_id']);
                $jdArr = json_decode($jdJson,1);
                $jdArray = $jdArr['jingdong_service_promotion_goodsInfo_responce'];
                $jdArray = $jdArray['getpromotioninfo_result'];
                $jdRes = json_decode($jdArray,1);
                $jd_result = $jdRes['result'][0];
                $goods_id = $jd_result['skuId'];//宝贝id
                $goods_title = $jd_result['goodsName'];//宝贝标题
                $goods_img = $jd_result['imgUrl'];//宝贝主图
                $goods_price = $jd_result['unitPrice'];//商品价格
                $sale_count = $jd_result['inOrderCount'];//宝贝销量
                // $goods_id = $arr['detail']['sku_id'];//宝贝id
                // $goods_title = $arr['detail']['ad_title'];//宝贝标题
                // $goods_img = '//img13.360buyimg.com/n1/s450x450_'.$arr['att_imgs'][0];//宝贝主图
                // $goods_price = $arr['detail']['sku_price'];//商品价格
                // $sale_count = $this->jd_sales($one['link_id']);//销量
                $remark_count = $arr['detail']['commentnum'];//累计评价
                $shop_name = $arr['shop']['name'];//店铺名称
                $rank_record = json_encode(compact('goods_id','goods_title','goods_img','goods_price','sale_count','remark_count','shop_name'));
                //更新竞品
                $insert['add_time'] = time();
                $insert['last_update_time'] = time();
                $insert['shop_name'] = $shop_name;
                $sql = Db::name('competitor')->where('id',$one['id'])->update($insert);
                //保存记录
                $insert2 = [
                    'for_table'=>'competitor',
                    'rank_record'=>$rank_record,
                    'user_id' =>$one['user_id'],
                    'goods_id'=>$one['link_id'],
                    'goods_title'=>$goods_title,
                    'goods_img' =>$goods_img,
                    'goods_price'=>$goods_price,
                    'sale_count'=>$sale_count,
                    'remark_count'=>$remark_count,
                    'add_time'=>time(),
                    'for_id'=>$one['id'],
                    'terrace'=>$one['terrace'],
                    'shop_name'=>$shop_name,
                    'update_time'=>time()
                ];
                $sql2 = Db::name('rank_record')->insert($insert2);
                $one = Db::name('competitor')->where('last_update_time','between',[$timestart,$timeend])->find();
            }
        }
    }
}
