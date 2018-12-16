<?php
/**
*
* example目录下为简单的支付样例，仅能用于搭建快速体验微信支付使用
* 样例的作用仅限于指导如何使用sdk，在安全上面仅做了简单处理， 复制使用样例代码时请慎重
* 请勿直接直接使用样例对外提供服务
* 
**/

ini_set('display_errors',1);

require_once WCC_WEPAY_PLUGIN_PATH."lib/WxPay.Data.php";
require_once WCC_WEPAY_PLUGIN_PATH."lib/WxPay.Api.php";
require_once WCC_WEPAY_PLUGIN_PATH."inc/WxPay.Config.php";

if(isset($_REQUEST["out_trade_no"]) && $_REQUEST["out_trade_no"] != "") {
	try{

		$out_trade_no = $_REQUEST["out_trade_no"];
		$input = new WxPayOrderQuery();
		$input->SetOut_trade_no($out_trade_no);
		$config = new WxPayConfig();
                $result = WxPayApi::orderQuery($config, $input);

                echo $result['trade_state'];
	} catch(Exception $e) {
		
	}
	exit();
}
?>
