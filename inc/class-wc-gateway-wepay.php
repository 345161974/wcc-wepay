<?php


class WC_Gateway_Wepay extends WC_Payment_Gateway {
    
    public function __construct() {
        // 必要的字段
        $this->id = WCC_WEPAY_GATEWAY_ID;
        $this->icon = WCC_WEPAY_PLUGIN_URL.'assets/wepay.png';
        $this->has_fields = false;
        $this->method_title = '微信';
        $this->method_description = '微信支付网关设置';
        
        // 必须调用的方法
        $this->init_form_fields();
        $this->init_settings();
        
        // 设置request或者response对象会用到的变量
        $this->title            = $this->get_option('title');
        $this->description      = $this->get_option('description');
        $this->app_id           = $this->get_option('app_id');
        $this->mch_id           = $this->get_option('mch_id');
        $this->api_key          = $this->get_option('api_key');

        // 保存后台设置的数据
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        
        // 实例化异步通知的处理
        new WC_Gateway_Wepay_Response($this);
        
    }
    
    /*
     * 处理支付宝付款
     * 生成完整的支付宝付款的Url
     * @param int $order_id 
     */
    public function process_payment($order_id) {
        
        $request = new WC_Gateway_Wepay_Request($this);
        $order = wc_get_order($order_id);
        
        $url = $request->get_return_url($order);

        return array(
            'result'    =>  'success',
            'redirect'  =>  get_option( 'wepay_qrcode_url' ) . '&out_trade_no=' . $request->getOutTradeNo() . '&url=' . urlencode($url) . '&return_url=' . urlencode($request->getReturnUrl())
        );
    }
    
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                    'title'   => "启用/禁用",
                    'type'    => 'checkbox',
                    'label'   => "是否启用微信支付网关，勾选为启用",
                    'default' => 'yes',
            ),
            'title' => array(
                    'title'       => "标题",
                    'type'        => 'text',
                    'description' => '在结算时看到的当前支付方式的名称',
                    'default'     => '微信支付',
                    'desc_tip'    => true,
            ),
            'description' => array(
                    'title'       => '描述',
                    'type'        => 'text',
                    'desc_tip'    => true,
                    'description' => '结算时当前支付方式的描述',
                    'default'     => '将使用微信支付付款,打开微信App扫描二维码',
            ),
            'app_id'    => array(
                    'title'       => 'APPID',
                    'type'        => 'text',
                    'desc_tip'    => true,
                    'description' => '腾讯提供的微信支付APPID',
                    'default'     => '',
            ),
            'mch_id' => array(
                    'title'       => '商户号ID',
                    'type'        => 'text',
                    'desc_tip'    => true,
                    'description' => '支付宝给你提供的商户号ID，在微信商户平台产品中心->开发配置配置',
                    'default'     => '',
            ),
            'api_key'  => array(
                    'title'       => '商户支付API密钥',
                    'type'        => 'text',
                    'desc_tip'    => true,
                    'description' => '你创建的微信支付API密钥，在微信商户平台账户中心->API安全设置',
                    'default'     => '',
            )
        );
    }
    
    
}


