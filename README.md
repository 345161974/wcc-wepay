# wcc-wepay

woocommerce wepay plugin, woocommerce微信支付插件

### 重要的事情说三遍：
***本项目代码仅供参考学习!***

***本项目代码仅供参考学习!***

***本项目代码仅供参考学习!***

## 使用前请先注意

* 如果PHP版本是5.x版本，请注意lib/WxPay.Api.php文件中public static function notify($config, $callback, &$msg)...方法

> 因为PHP 7.x没有$GLOBALS['HTTP_RAW_POST_DATA']用法，7.x中用file_get_contents('php://input')替换5.x中$GLOBALS['HTTP_RAW_POST_DATA']，请知悉，代码如下：

``` php

        /**
 	 * 
 	 * 支付结果通用通知
 	 * @param function $callback
 	 * 直接回调函数使用方法: notify(you_function);
 	 * 回调类成员函数方法:notify(array($this, you_function));
 	 * $callback  原型为：function function_name($data){}
 	 */
	public static function notify($config, $callback, &$msg)
	{
                // PHP 5.x可以开启该注释
                /* 
		if (!isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
                    file_put_contents(WCC_WEPAY_PLUGIN_PATH.'WC_Gateway_Wepay_Response.txt', 'FALSE,未收到数据'.date("Y-m-d H:i:s",time()).PHP_EOL, FILE_APPEND);
			# 如果没有数据，直接返回失败
			return false;
		}
                */

		//如果返回成功则验证签名
		try {
			//获取通知的数据
			//$xml = $GLOBALS['HTTP_RAW_POST_DATA']; // PHP 5.x版本用这个
                        $xml = file_get_contents('php://input'); // PHP 7.x版本用这个
			$result = WxPayNotifyResults::Init($config, $xml);
		} catch (WxPayException $e){
			$msg = $e->errorMessage();
			return false;
		}
		
		return call_user_func($callback, $result);
	}

```

### 插件已支持功能介绍(2019.04.21更新)

* 支持最基本的PC扫码支付(基于PHP 7.x)

* 支付完成自动跳转(微信demo演示并未提供该参数:return_url,需自己实现该功能...支付宝是自带了自动跳转return_url的参数)

* 增加插件的清理工作:uninstall.php

* 浏览器兼容性测试，支持Chrome,Firefox,IE 11

### 插件已支持功能介绍(2018.12.18更新)

* 支持最基本的PC扫码支付

* 支付完成自动跳转(微信demo演示并未提供该参数:return_url,需自己实现该功能...支付宝是自带了自动跳转return_url的参数)

## 运行环境(2018.12.16更新)

> PHP 7.x

> 成功安装WooCommerce的WordPress系统

> WordPress:WordPress 4.9.9

> WooCommerce:3.5.0

> 微信支付SDK:php_sdk_v3.0.9

## 演示使用

![wcc-wepay-show](https://user-images.githubusercontent.com/3973297/50052453-16599000-015f-11e9-80de-238bd8f167f7.gif)

## 如何使用

* 设置固定链接格式（由于微信异步回调对回调链接有要求，不可以设置带参数的）

![wordpress_link_setting](https://user-images.githubusercontent.com/3973297/50052429-e5795b00-015e-11e9-9202-388338e57cf7.png)

* 安全证书放置于cert目录下即可，插件会去该路径找安全证书

![cert_path](https://user-images.githubusercontent.com/3973297/50052434-ead6a580-015e-11e9-9b26-e816ad6852f0.png)

* 上传插件，开启插件

![enable_wcc_wepay_plugin](https://user-images.githubusercontent.com/3973297/50052435-f32ee080-015e-11e9-9bcb-6f09e44f3668.png)

* WooCommerce付款设置启用微信支付

![wcc_enable_wepay_setting](https://user-images.githubusercontent.com/3973297/50052439-fa55ee80-015e-11e9-8e4d-cded88e47b3d.png)

* WooCommerce微信支付设置支付参数

![wcc_wepay_setting_args](https://user-images.githubusercontent.com/3973297/50052441-fd50df00-015e-11e9-9678-251c10708823.png)

![configure_wepay_mch_end](https://user-images.githubusercontent.com/3973297/50052442-ff1aa280-015e-11e9-828d-83e3d2634156.png)

## 上述设置成功,即可使用微信支付了.


