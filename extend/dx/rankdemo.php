<?php
	header("Content-type:text/html;charset=utf-8");
	require_once "dx/DxClient.php";
	$DxClient =  new DxClient();
	$DxClient->appkey= "12372315";
	$DxClient->secretKey= "37B3F22CEF0141D9DF7243C92776608D";
	$req = new DxRankGetRequest();
	/**
	* setMedia必须，0为PC，1为无线 会影响setTagId的设置,会影响sort设置
	**/
	$req->setMedia(1);
	/**
	* setMedia为0时，可选值：0-默认，1-人气，2-销量，3-信用，4-最新，5-价格升，6-价格降，
	* 1时，可选值：0-默认，2-销量，5-价格升，6-价格降，
	**/
	$req->setSort(0);
	/**
	* setPattern必须，0为模糊，1为精准
	**/
	$req->setPattern(0);
	/**
	* setQ搜索词 必须
	**/
	$req->setQ('牛仔裤');//关键词
	/**
	* setWwId当setPattern(0)时为必须
	**/
	$req->setWwId('*');//旺旺号
	/**
	* setGoodId当setPattern(1)时为必须
	**/
	//$req->setGoodId('145134645');
	/**
	* setStartPage必须，开始查找的页码
	**/
	$req->setStartPage(1);//起始查找也
	/**
	* setPageStep可选，默认为一次2页，，假设第一页开始，设置为2，则查找1,2
	**/
	$req->setPageStep(2);//步长 每次查询几页
	/**
	* setPrice可选，最小价，最大价,0 - 不设置
	**/
	$req->setPrice(0, 0);
	/**
	* setType可选，会影响setTagId的设置
	* 选择PC且为所有宝贝时
	**/
	//$req->setType("all");
	/**
	* setTagId可选
	* 选择PC且为所有宝贝(all)时，可选值：0-包邮，1-赠送退货运费险，2-货到付款，3-海外商品，4-二手，5-天猫，6-正品保证，
	* 7-24小时内发货，8-7+天内退货，9-旺旺在线，10-信用卡支付,11-折扣促销，12-新品
	* 选择PC且为天猫时(tmall)，可选值：0-包邮，1-赠送退货运费险，2-货到付款，3-海外商品，4-二手，6-正品保证，
	* 7-24小时内发货，9-旺旺在线，10-信用卡支付,11-折扣促销，12-新品
	* 选择无线时(不管所有宝贝还是天猫)，可选值：13-免运费，14-天猫，15-全球购，16-消费者保障，17-手机专享价，18-淘金币，19-货到付款，20-七天退换，21-促销
	*
	**/
//	$req->setTagId("1,2,3");
	/**
	* setArea可选，直接传地址
	**/
	//$req->setArea("浙江");
	
	$resp = $DxClient->execute($req);
	echo "<pre>";
	print_r($resp);
?>
数据返回格式简单说明：
<div style="background:#ccc">
stdClass Object
(
    [info] => stdClass Object
        (
            [totalpage] => 100  //所有宝贝页数
            [startpage] => 1 //此次查询起始页
            [nextpage] => 4  //下一页
            [pagestep] => 3 //共查询页
        )

    [data] => stdClass Object
        (
            [items] => Array
                (
                    [0] => stdClass Object
                        (
                            [id] => 368364225 //宝贝ID
                            [pos] => 23 //位置
                            [page] => 3 //页码
                            [title] =>###################################################### //标题
                            [url] => ###################################################### //宝贝连接
                            [img] => ###################################################### //主图
							[isp4p] => 是否直通车
                        )

                )

        )

)
</div>
