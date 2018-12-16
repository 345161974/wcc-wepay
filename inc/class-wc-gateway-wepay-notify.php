<?php
/**
*
* example目录下为简单的支付样例，仅能用于搭建快速体验微信支付使用
* 样例的作用仅限于指导如何使用sdk，在安全上面仅做了简单处理， 复制使用样例代码时请慎重
* 请勿直接直接使用样例对外提供服务
* 
**/

//ini_set('date.timezone','Asia/Shanghai');
require_once WCC_WEPAY_PLUGIN_PATH."lib/WxPay.Api.php";
require_once WCC_WEPAY_PLUGIN_PATH.'lib/WxPay.Notify.php';
require_once WCC_WEPAY_PLUGIN_PATH."inc/WxPay.Config.php";
require_once WCC_WEPAY_PLUGIN_PATH.'inc/log.php';

//初始化日志
$logHandler= new CLogFileHandler(WCC_WEPAY_PLUGIN_PATH."logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);

		$config = new WxPayConfig();
		$result = WxPayApi::orderQuery($config, $input);
		Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}

	/**
	*
	* 回包前的回调方法
	* 业务可以继承该方法，打印日志方便定位
	* @param string $xmlData 返回的xml参数
	*
	**/
	public function LogAfterProcess($xmlData)
	{
		Log::DEBUG("call back， return xml:" . $xmlData);
		return;
	}
	
	//重写回调处理函数
	/**
	 * @param WxPayNotifyResults $data 回调解释出的参数
	 * @param WxPayConfigInterface $config
	 * @param string $msg 如果回调处理失败，可以将错误信息输出到该方法
	 * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
	 */
	public function NotifyProcess($objData, $config, &$msg)
	{
                date_default_timezone_set('PRC'); //设置中国时区
		$data = $objData->GetValues();
                file_put_contents(WCC_WEPAY_PLUGIN_PATH.'notify.txt', 'out_trade_no='.$data['out_trade_no'].date("Y-m-d H:i:s",time()).PHP_EOL, FILE_APPEND);
		//TODO 1、进行参数校验
		if(!array_key_exists("return_code", $data) 
			||(array_key_exists("return_code", $data) && $data['return_code'] != "SUCCESS")) {
			//TODO失败,不是支付成功的通知
			//如果有需要可以做失败时候的一些清理处理，并且做一些监控
			$msg = "异常异常";
			return false;
		}
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}

		//TODO 2、进行签名验证
		try {
			$checkResult = $objData->CheckSign($config);
			if($checkResult == false){
				//签名错误
				Log::ERROR("签名错误...");
				return false;
			}
		} catch(Exception $e) {
			Log::ERROR(json_encode($e));
		}

		//TODO 3、处理业务逻辑
		Log::DEBUG("call back:" . json_encode($data));
		$notfiyOutput = array();
		
		
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			file_put_contents(WCC_WEPAY_PLUGIN_PATH.'notify.txt', $msg.date("Y-m-d H:i:s",time()).PHP_EOL, FILE_APPEND);
			return false;
                } else {
                    $msg = "订单查询成功";
                    file_put_contents(WCC_WEPAY_PLUGIN_PATH.'notify.txt', $msg.$data["out_trade_no"].date("Y-m-d H:i:s",time()).PHP_EOL, FILE_APPEND);
                    
                    // 判断订单是否存在
                    $order = wc_get_order($data["out_trade_no"]);
                    
                    file_put_contents(WCC_WEPAY_PLUGIN_PATH.'notify.txt', '获得订单对象'.date("Y-m-d H:i:s",time()).PHP_EOL, FILE_APPEND);
                    
                    // 增加一条订单备注
                    $date = date("Y-m-d H:i:s",time());
                    $msg = $date.' 顾客使用微信支付完成支付';
                    $order->add_order_note($msg);
                    file_put_contents(WCC_WEPAY_PLUGIN_PATH.'notify.txt', '添加订单备注'.date("Y-m-d H:i:s",time()).PHP_EOL, FILE_APPEND);
                    
                    // 更新订单状态、设置正确的库存
                    $order->payment_complete();
                    
                    file_put_contents(WCC_WEPAY_PLUGIN_PATH.'notify.txt', '更新订单状态、设置正确的库存'.date("Y-m-d H:i:s",time()).PHP_EOL, FILE_APPEND);
                    
                    // 保存交易号
                    update_post_meta($order->get_id(), 'wepay_trade_no', $data["out_trade_no"]);
                    
                    file_put_contents(WCC_WEPAY_PLUGIN_PATH.'notify.txt', '保存交易号'.date("Y-m-d H:i:s",time()).PHP_EOL, FILE_APPEND);
                    
                }
		
		file_put_contents(WCC_WEPAY_PLUGIN_PATH.'notify.txt', '回调调用true'.date("Y-m-d H:i:s",time()).PHP_EOL, FILE_APPEND);
		
		return true;
	}
}
//
//$config = new WxPayConfig();
//Log::DEBUG("begin notify");
//$notify = new PayNotifyCallBack();
//$notify->Handle($config, false);
