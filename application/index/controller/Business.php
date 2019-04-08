<?php
namespace app\index\controller;

use app\common\controller\Frontend;
use think\Config;
use think\Cookie;
use think\Hook;
use think\Session;
use think\Validate;
use think\Db;
use think\Request;
use think\Loader;
use app\index\controller\Https;
Loader::import('dx.DxClient',EXTEND_PATH);
/**
 * 任务管理
 */
class Business extends Frontend
{

    protected $layout = 'default';
    protected $noNeedLogin = ['*'];
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
     * 淘宝竞价排名
     */
    public function taobao()
    {
        return $this->view->fetch();
    }
    /**
     * 排名搜索
     */
    public function get_tb(Request $request)
    {
        if($request->isPost()){
            $json = input('post.json');
            $arr = json_decode($json,1);
            $start_page = $arr['page_start'];//起始页
            $end_page = $arr['page_end'];//结束页
            $search_mode = $arr['search_mode'];//客户端类型
            $keywords = $arr['keyword'];//关键词
            $url = $arr['target'];//宝贝链接
            $host = parse_url($url)['host'];
            if(strpos($host,'taobao') !==false){
                //获取淘宝宝贝id
                $link_id = tbid($url);
            }elseif(strpos($host,'tmall') !==false){
                //获取天猫宝贝id
                $link_id = tmid($url);
            }else{
                return ['code'=>0,'msg'=>'请输入合法的宝贝链接','leadTime'=>''];
            }
            $t1 = microtime(true);
            //请求淘宝接口
            for($i=$start_page;$i<=$end_page;$i+=2){
                $json = '';
                $json = $this->DxApi($keywords,$link_id,$i);
                if(is_string($json)){
                    $arr = json_decode($json,1);
                    if(array_key_exists('data',$arr)){
                        $items= $arr['data']['items'];
                    } 
                }
                if(!empty($items)){
                    break; 
                }
            }
            $t2 = microtime(true);
            $leadTime = '耗时'.round($t2-$t1,3).'秒';
            if(empty($items)){
                return ['code'=>0,'msg'=>'共查询了'.$end_page.'页,未找到相应宝贝的排名','leadTime'=>$leadTime];
            }
            $items = $items[0];
            return ['code'=>1,'msg'=>'ok','info'=>$items];
        }
    }
    /**
     * 京东竞价排名
     */
    public function jingdong()
    {
        return $this->view->fetch();
    }
    /**
     * 京东排名搜索
     */
    public function get_jd(Request $request)
    {
        if($request->isPost()){
            $json = input('post.json');
            $arr = json_decode($json,1);
            $start_page = $arr['page_start'];//起始页
            $end_page = $arr['page_end'];//结束页
            $search_mode = $arr['search_mode'];//客户端类型
            $keywords = $arr['keyword'];//关键词
            $url = $arr['target'];//宝贝链接
            $host = parse_url($url)['host'];
            if(strpos($host,'jd') !==false){
                $link_id = jdid($url);
            }else{
                return ['code'=>0,'msg'=>'请输入合法的宝贝链接','leadTime'=>''];
            }
            //请求京东接口
            $arr = $this->jdApi($keywords,$link_id);
            if(empty($arr)){
                return ['code'=>0,'msg'=>'共查询了'.$end_page.'页,未找到相应宝贝的排名','leadTime'=>''];
            }
            $goods_title = $arr['Content']['warename'];//商品标题
            $goods_img = '//img13.360buyimg.com/n1/s450x450_'.$arr['Content']['imageurl'];//商品主图
            $page = (int)$arr['page'];//页码
            $position = $arr['pos'];//位置
            $info['title'] = $goods_title;
            $info['img'] = $goods_img;
            $info['page'] = $page;
            $info['pos'] = $position;
            return ['code'=>1,'msg'=>'ok','info'=>$info];
        }
    }
    /**
     * 排名监控
     */
    public function monitor()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $this->relationSearch = true;
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = model('Links')
                ->where($where)
                ->order($sort, $order)
                ->count();
            $list = model('Links')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        $this->assign('title', '排名监控');
        return $this->view->fetch();
    }
    /**
     * 保存监控排名
     */
    public function save_monitor(Request $request)
    {
        set_time_limit(0);
        header("Content-Type:text/html;charset=UTF-8");
        if($request->isPost()){
            $data = input('post.formdata');
            $data = json_decode($data,true);
            $remark = $data['tip_caption'];
            //获取宝贝链接
            $url = $data['links'];
            //获取宝贝关键字
            $keywords = $data['keywords'];
            //数据去空格
            if(empty(trim($url)) || empty(trim($keywords))) return ['code'=>0,'msg'=>'宝贝链接和关键词不能为空'];
            //判断宝贝链接的合法性
            if(strpos($url,'jd.com')===false && stripos($url, 'taobao.com')===false && stripos($url, 'tmall.com')===false) return ['code'=>0,'msg'=>'请输入合法的宝贝链接'];
            $host = parse_url($url)['host'];
            if(strpos($host,'jd') !==false){
                //获取京东宝贝id
                $link_id = jdid($url);
                $terrace = '京东';
            }elseif(strpos($host,'taobao') !==false){
                //获取淘宝宝贝id
                $link_id = tbid($url);
                $terrace = '淘宝';
            }elseif(strpos($host,'tmall') !==false){
                //获取天猫宝贝id
                $link_id = tmid($url);
                $terrace = '天猫';
            }else{
                return ['code'=>0,'msg'=>'请输入合法的宝贝链接'];
            }
            $user_id = $this->auth->id;//用户id
            $is_exist = Db::name('links')->where(['user_id'=>$user_id,'link_id'=>$link_id])->find();
            if($is_exist){
                return ['code'=>0,'msg'=>'该宝贝已经添加过'];
            }
            //请求淘大象淘宝接口
            if($terrace=="淘宝" || $terrace=="天猫"){
                for($i=1;$i<=13;$i+=2){
                    $json = '';
                    $json = $this->DxApi($keywords,$link_id,$i);
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
                    return ['code'=>0,'msg'=>'获取排名失败'];
                }
                //获取接口传来的数据
                $tbApiUrl = 'https://acs.m.taobao.com/h5/mtop.taobao.detail.getdetail/6.0/?data=%7B%22itemNumId%22%3A%22'.$link_id.'%7D&qq-pf-to=pcqq.group';
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
            }
            //请求京东接口
            if($terrace=='京东'){
                $data = $this->jdApi($keywords,$link_id);
                if(empty($data)){
                    return ['code'=>0,'msg'=>'获取排名失败'];
                }
                $jdJson = $this->jd_sales($link_id);
                $jdArr = json_decode($jdJson,1);
                $jdArray = $jdArr['jingdong_service_promotion_goodsInfo_responce'];
                $jdArray = $jdArray['getpromotioninfo_result'];
                $jdRes = json_decode($jdArray,1);
                $jd_result = $jdRes['result'][0];
                $items = $data;
                $goods_title = $data['Content']['warename'];//商品标题
                $shop_name = $data['shop_name'];//店铺名称
                $goods_img = '//img13.360buyimg.com/n1/s450x450_'.$data['Content']['imageurl'];//商品主图
                $goods_price = $data['dredisprice'];//商品价格
                $comment_num = $data['commentcount'];//商品评价数量
                $sale_count = $jd_result['inOrderCount'];//商品销量
                $page = (int)$data['page'];//页码
                $position = $data['pos'];//位置
                $strJson = [];
                $strJson['page'] = $page;
                $strJson['pos'] = $position;
                $strJson = json_encode($strJson);
            }
            //处理业务
            $insert = [
                'goods_link'=>$url,
                'user_id'=>$user_id,
                'link_id'=>$link_id,
                'remark'=>$remark,
                'update_time'=>time(),
                'create_time'=>time(),
                'terrace'=>$terrace,
                'keywords_num'=>1,
                'goods_title'=>$goods_title,
                'shop_name'=>$shop_name,
                'goods_img'=>$goods_img,
                'goods_price'=>$goods_price
            ];
            $sql = Db::name('links')->insertGetId($insert);
            if($sql){
                $insert2 = ['link_id'=>$link_id,
                    'keywords'=>$keywords,
                    'user_id'=>$user_id,
                    'mobile_update_time'=>time(),
                    'create_time'=>time(),
                    'page'  =>$page,
                    'position'=>$position,
                    'device_type'=>'mobile',
                    'for_id' =>$sql,
                    'terrace'=>$terrace,
                    'mobile'=>$strJson
                ];
                $sql2 = Db::name('links_keywords')->insert($insert2);
                $insert3 = [
                    'rank_record'=>json_encode($items),
                    'keywords'=>$keywords,
                    'user_id'=>$user_id,
                    'goods_id'=>$link_id,
                    'add_time'=>time(),
                    'update_time'=>time(),
                    'device_type'=>'mobile',
                    'page'=>$page,
                    'position'=>$position,
                    'for_id'=>$sql,
                    'terrace'=>$terrace,
                    'shop_name'=>$shop_name,
                    'goods_title'=>$goods_title,
                    'goods_img'=>$goods_img,
                    'goods_price'=>$goods_price,
                    'remark_count'=>$comment_num,
                    'sale_count' =>$sale_count,
                    'for_table'=>'monitor'
                ];
                $sql3 = Db::name('rank_record')->insert($insert3);
                return ['code'=>1,'msg'=>'添加成功','url'=>'/Business/monitor'];
            }else{
                return ['code'=>0,'msg'=>'添加失败'];
            }
        }
    }
    /**
     * 删除监控的宝贝
     */
    public function del_link(Request $request)
    {
        if($request->isPost()){
            $link_id = input('post.link_id');
            //删除宝贝
            $user_id = $this->auth->id;
            $sql = Db::name('links')->where(['link_id'=>$link_id,'user_id'=>$user_id])->delete();
            if($sql){
                //删除关键词 删除排名记录
                $sql_keywords = Db::name('links_keywords')->where(['link_id'=>$link_id,'user_id'=>$user_id])->delete();
                $sql_record = Db::name('rank_record')->where(['goods_id'=>$link_id,'user_id'=>$user_id])->delete();
                return ['code'=>1,'msg'=>'删除成功'];
            }else{
                return ['code'=>0,'msg'=>'删除失败'];
            }
        }
    }
    /**
     * 排名监控html
     */
    public function search(Request $request)
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $this->relationSearch = true;
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            $link_id = input('get.link_id');
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = Db::name('links_keywords')
                ->where($where)
                ->where('link_id',$link_id)
                ->order($sort, $order)
                ->count();
            $list = Db::name('links_keywords')
                ->where($where)
                ->where('link_id',$link_id)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        $link_id = input('get.link_id');
        $this->assign('link_id',$link_id);
        $this->assign('title', '排名详情');
        return $this->view->fetch();
    }
    /**
     * 排名详情
     */
    public function get_monitor_detail(Request $request)
    {
        if($request->isPost()){
            $for_id = input('post.ids');
            $info = Db::name('rank_record')
                    ->where(['for_id'=>$for_id,'for_table'=>'monitor','user_id'=>$this->auth->id])
                    ->order('update_time desc')
                    ->find(); 
            return ['code'=>1,'msg'=>'获取成功','data'=>$info];
        }
    }
    /**
     * 添加关键词
     */
    public function save_keywords(Request $request)
    {
        if($request->isPost()){
            $json = input('post.formdata');
            $arr = json_decode($json,1);
            $link_id = $arr['link_id'];//获取宝贝id
            $string = $arr['keywords'];
            $id = $arr['id'];
            $terrace = Db::name('links')->where('id',$id)->value('terrace');
            $keywords = explode(',',$string);
            if(empty($keywords)) return ['code'=>0,'msg'=>'关键词不能为空'];
            $count = count($keywords);
            $insert = [];
            foreach($keywords as $key=>$val){
                $insert[$key]['link_id'] = $link_id;
                $insert[$key]['keywords'] = $val;
                $insert[$key]['user_id'] = $this->auth->id;
                $insert[$key]['for_id'] = $id;
                $insert[$key]['terrace'] = $terrace;
                $insert[$key]['create_time'] = time();
            }
            $sql = Db::name('links_keywords')->insertAll($insert);
            if($sql){
                //更新关键词数量
                $keywords_num = Db::name('links')->where(['user_id'=>$this->auth->id,'link_id'=>$link_id])->value('keywords_num');
                $keywords_num = $keywords_num+$count;
                $sql2 = Db::name('links')->where(['user_id'=>$this->auth->id,'link_id'=>$link_id])->update(['keywords_num'=>$keywords_num]);
                return ['code'=>1,'msg'=>'添加成功','url'=>'/Business/search?link_id='.$link_id.'&id='.$id];
            }else{
                return ['code'=>0,'msg'=>'添加失败'];
            }
        }
    }
    /**
     * 竞品监控
     */
    public function contend()
    {   
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $this->relationSearch = true;
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = Db::name('competitor')
                ->where($where)
                ->order($sort, $order)
                ->count();
            $list = Db::name('competitor')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function ttss()
    {
        $data = file_get_contents('https://acs.m.taobao.com/h5/mtop.taobao.detail.getdetail/6.0/?data=%7B%22itemNumId%22%3A%22586490253311%22%7D&qq-pf-to=pcqq.group');
        echo $data;
    }
    /**
     * 保存竞品监控
     */
    public function save_contend(Request $request)
    {
        header("Content-Type:text/html; charset=utf-8");  
        if($request->isPost()){
            $json = input('post.formdata');
            $arr = json_decode($json,1);
            $remark = $arr['jptip'];
            $link = $arr['jpurl'];
            if(empty($link)) return ['code'=>0,'msg'=>'竞品链接不能为空'];
            //验证竞品是否已添加
            $is_exist = Db::name('competitor')->where(['user_id'=>$this->auth->id,'competitor_url'=>$link])->find();
            // if($is_exist) return ['code'=>0,'msg'=>'竞品已经存在,无法添加'];
            //判断宝贝链接的合法性
            if(strpos($link,'jd.com')===false && stripos($link, 'taobao.com')===false && stripos($link, 'tmall.com')===false) return ['code'=>0,'msg'=>'请输入合法的宝贝链接'];
            $host = parse_url($link)['host'];
            if(strpos($host,'jd') !==false){
                //获取京东宝贝id
                $link_id = jdid($link);
                $terrace = '京东';
            }elseif(strpos($host,'taobao') !==false){
                //获取淘宝宝贝id
                $link_id = tbid($link);
                $terrace = '淘宝';
            }elseif(strpos($host,'tmall') !==false){
                //获取天猫宝贝id
                $link_id = tmid($link);
                $terrace = '天猫';
            }else{
                return ['code'=>0,'msg'=>'请输入合法的宝贝链接'];
            }
            //请求接口获取数据
            if($terrace=='天猫' || $terrace=="淘宝"){
                $apiUrl = 'https://acs.m.taobao.com/h5/mtop.taobao.detail.getdetail/6.0/?data=%7B%22itemNumId%22%3A%22'.$link_id.'%7D&qq-pf-to=pcqq.group';
                $json = sendRequest($apiUrl,$params=[],'GET');
                $arr = json_decode($json,1);
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
            }else if($terrace=='京东'){
                $apiUrl = 'https://re.jd.com/cps/item/'.$link_id.'.html';
                $json = sendRequest($apiUrl,$params=[],'GET');
                $start = 'var pageData';
                $end = '};';
                $json = strbu($json,$start,$end);
                $json = $json."}";
                $json = trim(str_replace(' = ','', $json));
                $arr = json_decode($json,1);
                $jdJson = $this->jd_sales($link_id);
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
                // $sale_count = $this->jd_sales($link_id);//宝贝销量
                $remark_count = $arr['detail']['commentnum'];//累计评价
                $shop_name = $arr['shop']['name'];//店铺名称
                $rank_record = json_encode(compact('goods_id','goods_title','goods_img','goods_price','sale_count','remark_count','shop_name'));
            }else{
                return ['code'=>0,'msg'=>'请输入合法的宝贝链接'];
            }
            //竞品入库
            $insert['competitor_url'] = $link;
            $insert['link_id'] = $link_id;
            $insert['add_time'] = time();
            $insert['last_update_time'] = time();
            $insert['user_id'] = $this->auth->id;
            $insert['terrace'] = $terrace;
            $insert['goods_title'] = $goods_title;
            $insert['goods_img'] = $goods_img;
            $insert['goods_price'] = $goods_price;
            $insert['sale_count'] = $sale_count;
            $insert['remark_count'] = $remark_count;
            $insert['shop_name'] = $shop_name;
            $sql = Db::name('competitor')->insertGetId($insert);
            if($sql){
                //保存记录
                $insert2 = [
                    'for_table'=>'competitor',
                    'rank_record'=>$rank_record,
                    'user_id' =>$this->auth->id,
                    'goods_id'=>$link_id,
                    'goods_title'=>$goods_title,
                    'goods_img' =>$goods_img,
                    'goods_price'=>$goods_price,
                    'sale_count'=>$sale_count,
                    'remark_count'=>$remark_count,
                    'add_time'=>time(),
                    'for_id'=>$sql,
                    'terrace'=>$terrace,
                    'shop_name'=>$shop_name,
                    'update_time'=>time()
                ];
                $sql2 = Db::name('rank_record')->insert($insert2);
                return ['code'=>1,'msg'=>'竞品添加成功','url'=>'/business/contend'];
            }else{
                return ['code'=>0,'msg'=>'添加失败,请稍后尝试'];
            }
        }
    }
    /**
     * 竞品html
     */
    public function contend_detail()
    {
        return $this->view->fetch();
    }
    /**
     * 竞品详情
     */
    public function get_contend(Request $request)
    {
        if($request->isPost()){
            $for_id = input('post.ids');
            $info = Db::name('rank_record')
                    ->where(['for_id'=>$for_id,'for_table'=>'competitor','user_id'=>$this->auth->id])
                    ->order('update_time desc')
                    ->find(); 
            return ['code'=>1,'msg'=>'获取成功','data'=>$info];
        }
    }
    /**
     * 获取竞品title
     */
    public function get_competitor()
    {
        $for_id = input('post.for_id');
        $info = Db::name('rank_record')
                ->where(['for_table'=>'competitor','for_id'=>$for_id,'user_id'=>$this->auth->id])
                ->order('id asc')
                ->field('goods_title,update_time')
                ->select();
        $deal_data = $this->get_title($info,$type='goods_title');
        return ['code'=>1,'msg'=>'获取标题成功','data'=>$deal_data];
    }
    /**
     * 获取竞品图片
     */
    public function get_pic()
    {
        $for_id = input('post.for_id');
        $info = Db::name('rank_record')
                ->where(['for_table'=>'competitor','for_id'=>$for_id,'user_id'=>$this->auth->id])
                ->order('id asc')
                ->field('goods_img,update_time')
                ->select();
        $deal_data = $this->get_title($info,$type="goods_img");
        return ['code'=>1,'msg'=>'ok','data'=>$deal_data];
    }
    /**
     * 删除竞品
     */
    public function del_competitor(Request $request)
    {
        if($request->isPost()){
            $json = input('post.ids');
            $sql =Db::name('competitor')->where(['user_id'=>$this->auth->id,'id'=>$json])->delete();
            if($sql){
                return ['code'=>1,'msg'=>'删除成功','url'=>'/business/contend'];
            }else{
                return ['code'=>0,'msg'=>'删除失败'];
            }
        }
    }
    /**
     * 竞品统计图
     */
    public function get_echarts(Request $request)
    {
        if($request->isPost()){
            $json = input('post.jsonData');
            $arr = json_decode($json,1);
            $for_id = $arr['id'];//宝贝主键
            $fieldname = $arr['fieldname'];//操作名称
            $data = Db::name('rank_record')
                    ->where(['for_table'=>'competitor','for_id'=>$for_id])
                    ->order('add_time asc')
                    ->field('goods_price,sale_count,remark_count,add_time')
                    ->select();
            foreach($data as $key=>$val){
                $data[$key]['add_time'] = date('Y-m-d',$val['add_time']);
            }
            return ['code'=>1,'msg'=>'OK','json'=>$data];
        }
    }
    /**
     * 淘大象接口
     */
    public function DxApi($keywords,$goods_id,$startpage=1)
    {
        header("Content-Type:text/html;charset=UTF-8");
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
     * group
     */
    public function group()
    {
        $sqlQuery = Db::name('rank_record')
                    ->where(['goods_id'=>'549328344674','user_id'=>1])
                    ->order('update_time desc')
                    ->buildSql();
        $lists = Db::table($sqlQuery.'r')
                    ->where(['goods_id'=>'549328344674','user_id'=>1])
                    ->order('update_time desc')
                    ->group('keywords')
                    ->select();
        dump($lists);
    }
    /**
     * 监控标题
     */
    public function monitor_title($info)
    {
        $goods_title = $info[0]['goods_title'];
        $update_time = $info[0]['update_time'];
        $deal_data = [
            [
                ['goods_title'=>$goods_title,'update_time'=>$update_time]
            ]
        ];
        $n = 0;
        for($i=0;$i<count($info);$i++){
            if($goods_title !=$info[$i]['goods_title']){
               $deal_data[$n][] = ['goods_title'=>$info[$i]['goods_title'],'update_time'=>$info[$i]['update_time']];
               $n++;
               $deal_data[][] = ['goods_title'=>$info[$i]['goods_title'],'update_time'=>$info[$i]['update_time']];
               $goods_title = $info[$i]['goods_title'];
               $update_time = $info[$i]['update_time'];
            }
        }
        return $deal_data;
    }
    /**
     * 标题分组
     * @Author   zsw
     * @DataTime 2019-03-25T13:34:58+0800
     * @return   [type]                   [description]
     */
    public function get_title($info,$type='goods_title')
    {
        $start_title = $info[0][$type];
        $start_time = date('Y-m-d',$info[0]['update_time']);
        $deal_data = [
            ['start_title'=>$start_title,'start_time'=>$start_time]
        ];
        $n=0;
        for($i=0;$i<count($info);$i++){
            if($start_title != $info[$i][$type]){
                $deal_data[$n] = $deal_data[$n]+['end_title'=>$info[$i][$type],'end_time'=>date('Y-m-d',$info[$i]['update_time'])];
                $n++;
                $deal_data[] = ['start_title'=>$info[$i][$type],'start_time'=>date('Y-m-d',$info[$i]['update_time'])];
                $start_title = $info[$i][$type];
                $start_time = date('Y-m-d',$info[$i]['update_time']);
            }
        }
        foreach ($deal_data as $k => $vo) {
            if(!array_key_exists('end_time', $vo)){
                $deal_data[$k]['count_days'] = $this->get_days($vo['start_time'],date('Y-m-d',time()));
            }else{
                $deal_data[$k]['count_days'] = $this->get_days($vo['start_time'],$vo['end_time']);
            }
        }
        $keys = [];
        foreach ($deal_data as $key => $val) {
            $keys[] = $key; 
        }
        array_multisort($keys, SORT_DESC, $deal_data);
        return $deal_data;
    }
    /**
     * 获取日期差
     */
    public function get_days($start_time,$end_time)
    {
        $start_time = strtotime($start_time);
        $end_time = strtotime($end_time);
        $diff = ($end_time-$start_time)/86400;
        return $diff;
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
}