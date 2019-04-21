<?php
/*
 * 异步通知的处理类
 */

ini_set('display_errors',1);
class WC_Gateway_Wepay_Response
{
    private $gateway = null;
    
    public function __construct($gateway) {
        $this->gateway = $gateway;
        // 有关woocommerce回调地址可以参考文档:
        // https://docs.woocommerce.com/document/wc_api-the-woocommerce-api-callback/
        // https://stackoverflow.com/questions/37293309/how-to-get-the-payment-response-url-in-woocommerce
        add_action('woocommerce_api_wc_gateway_wepay', array($this, 'check_response'));
    }
    
    /*
     * 验证请求中的签名，确保请求是来自于微信
     */
    public function check_response() {
        
        date_default_timezone_set('PRC'); //设置中国时区 
        //echo "hello";
        //echo WCC_ALIPAY_PLUGIN_PATH;
        file_put_contents(WCC_WEPAY_PLUGIN_PATH.'WC_Gateway_Wepay_Response.txt', "check_response() 1 ".date("Y-m-d H:i:s",time()).PHP_EOL, FILE_APPEND);
        //exit;
        
        $config = new WxPayConfig();
        file_put_contents(WCC_WEPAY_PLUGIN_PATH.'WC_Gateway_Wepay_Response.txt', "check_response() 2 ".date("Y-m-d H:i:s",time()).PHP_EOL, FILE_APPEND);
        $notify = new PayNotifyCallBack();
        file_put_contents(WCC_WEPAY_PLUGIN_PATH.'WC_Gateway_Wepay_Response.txt', "check_response() 3 ".date("Y-m-d H:i:s",time()).PHP_EOL, FILE_APPEND);
        $notify->Handle($config, false);
        file_put_contents(WCC_WEPAY_PLUGIN_PATH.'WC_Gateway_Wepay_Response.txt', "check_response() 4 ".date("Y-m-d H:i:s",time()).PHP_EOL, FILE_APPEND);
    }
    
}

