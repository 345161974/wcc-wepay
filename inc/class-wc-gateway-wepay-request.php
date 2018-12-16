<?php

header("Content-type:text/html;charset=utf-8");
require_once WCC_WEPAY_PLUGIN_PATH."lib/WxPay.Api.php";
require_once WCC_WEPAY_PLUGIN_PATH."inc/WxPay.NativePay.php";
require_once WCC_WEPAY_PLUGIN_PATH."inc/WxPay.Config.php";
require_once WCC_WEPAY_PLUGIN_PATH.'inc/log.php';

/**
 * 生成合法的请求url
 */
class WC_Gateway_Wepay_Request
{
    private $gateway = null;
    private $return_url = '';
    private $out_trade_no = '';
    
    public function __construct($gateway) {
        $this->gateway = $gateway;
        $this->notify_url = WC()->api_request_url('WC_Gateway_Wepay');
        file_put_contents(WCC_WEPAY_PLUGIN_PATH.'url.txt', WC()->api_request_url('WC_Gateway_Wepay').date("YmdHis").PHP_EOL, FILE_APPEND);
    }
    
    // 获得商户交易号
    public function getOutTradeNo()
    {
        return $this->out_trade_no;
    }
    
    // 获得跳转url
    public function getReturnUrl()
    {
        return $this->return_url;
    }


    /**
     * 生成微信支付二维码(模式二)
     * @param type $order 订单信息
     * @return string url
     */
    public function get_return_url($order) {
        
        // 刷卡支付实现类
        $notify = new NativePay();
        
        // 支付商品配置类
        $input = new WxPayUnifiedOrder();
        
        $product_body = '';
        
                ## For WooCommerce 3+ ##
//        // Getting an instance of the WC_Order object from a defined ORDER ID
//        $order = wc_get_order( $order_id ); 

        // Iterating through each "line" items in the order
        foreach ($order->get_items() as $item_id => $item_data) {

            // Get an instance of corresponding the WC_Product object
            $product = $item_data->get_product();
            $product_name = $product->get_name(); // Get the product name

            $item_quantity = $item_data->get_quantity(); // Get the item quantity

            $item_total = $item_data->get_total(); // Get the item line total

            // Displaying this data (to check)
            //$product_body .= '商品: '.$product_name.' | 数量: '.$item_quantity.' | 总价: '. number_format( $item_total, 2 );
            $product_body .= $product_name.' ';
        }
        
        $input->SetBody($product_body);
        $input->SetAttach('编号为#'.$order->get_id().'的订单');
        //$this->out_trade_no = "sdkphp123456789".date("YmdHis");
        
        //$this->out_trade_no = "uzheshop1234567".date("YmdHis");
        $this->out_trade_no = $order->get_id();
        $this->return_url = $order->get_checkout_order_received_url();

        //$input->SetOut_trade_no($this->out_trade_no);
        $input->SetOut_trade_no($order->get_id());
        // 最小单位是分
        //$input->SetTotal_fee($order->get_total() * 100);
        $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis"));
        //$input->SetTime_expire(date("YmdHis", time() + 1200));
        

        
        $input->SetGoods_tag('编号为#'.$order->get_id().'的订单');
        //$input->SetNotify_url("http://paysdk.weixin.qq.com/notify.php");
        $input->SetNotify_url($this->notify_url);
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id($order->get_id());

        $result = $notify->GetPayUrl($input);
//        print_r($result);
        $url2 = $result["code_url"];
        
        return $url2;
    }
    
}