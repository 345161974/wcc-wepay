<?php

/*
 * Plugin Name: WooCommerce Wepay Gateway
 * Description: 微信网关
 * Version: 1.0
 * Author: Lucas
 * Author URI: https://www.yuanpengfei.com
 */

// 防止文件直接被访问
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// 定义常量
define('WCC_WEPAY_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WCC_WEPAY_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WCC_WEPAY_GATEWAY_ID', 'wcc_wepay');

// 创建二维码扫描页面
register_activation_hook( __FILE__, 'create_wepay_qrcode_page' );

function create_wepay_qrcode_page() {

        $page_id = -1;

        // 设置付款二维码页面
        $author_id = 1;
        $slug = 'wepay-page';
        $title = 'Wepay_Qrcode_Page';

        $wepay_page = get_page_by_title( $title );
        // 检查该页面是否存在,如果不存在就新建
        if ( null == $wepay_page) {

            $wepay_page = array(
                    'comment_status'        => 'closed',
                    'ping_status'           => 'closed',
                    'post_author'           => $author_id,
                    'post_name'             => $slug,
                    'post_title'            => $title,
                    'post_status'           => 'publish',
                    'post_type'             => 'page'
            );

            // 此处要记录$post_id的值,此值为: www.xxx.com/?page_id=$post_id 未来显示二维码页面
            $page_id = wp_insert_post( $wepay_page );

            if ( !$page_id ) {
                wp_die( 'Error creating template page' );
            } else {
                    // 此处要记录www.xxx.com/?page_id=$post_id该链接
                    update_post_meta( $page_id, '_wp_page_template', 'template_wepay_qrcode_page.php' );
                    $wepay_qrcode_url = get_site_url(null, '/?page_id='.$page_id);
                    update_option( 'wepay_qrcode_url', $wepay_qrcode_url );

            }
        } else {
            $wepay_qrcode_url = get_site_url(null, '/?page_id='.$wepay_page->ID);
            update_option( 'wepay_qrcode_url', $wepay_qrcode_url );
        }

}

// 创建付款查询页面
register_activation_hook( __FILE__, 'create_wepay_order_query_page' );

function create_wepay_order_query_page() {

        $page_id = -1;

        // 设置付款二维码页面
        $author_id = 1;
        $slug = 'wepay-page';
        $title = 'Wepay_Order_Query_Page';

        $wepay_page = get_page_by_title( $title );
        // 检查该页面是否存在,如果不存在就新建
        if ( null == $wepay_page) {

            $wepay_page = array(
                    'comment_status'        => 'closed',
                    'ping_status'           => 'closed',
                    'post_author'           => $author_id,
                    'post_name'             => $slug,
                    'post_title'            => $title,
                    'post_status'           => 'publish',
                    'post_type'             => 'page'
            );

            // 此处要记录$post_id的值,此值为: www.xxx.com/?page_id=$post_id 未来显示二维码页面
            $page_id = wp_insert_post( $wepay_page );

            if ( !$page_id ) {
                wp_die( 'Error creating template page' );
            } else {
                    // 此处要记录www.xxx.com/?page_id=$post_id该链接
                    update_post_meta( $page_id, '_wp_page_template', 'template_wepay_order_query_page.php' );
                    $wepay_order_query_url = get_site_url(null, '/?page_id='.$page_id);
                    update_option( 'wepay_order_query_url', $wepay_order_query_url );

            }
        } else {
            $wepay_order_query_url = get_site_url(null, '/?page_id='.$wepay_page->ID);
            update_option( 'wepay_order_query_url', $wepay_order_query_url );
        }

}

// 针对二维码页面,查询订单页面进行模板重定向
add_action( 'template_include', 'wepay_redirect' );
function wepay_redirect( $template ) {

    // 跳转到二维码扫描页面
    if ( is_page_template( 'template_wepay_qrcode_page.php' )) {

        $template = WCC_WEPAY_PLUGIN_PATH . 'inc/class-wc-gateway-wepay-qrcode-show.php';
    }
    
    // 跳转到订单查询页面
    if ( is_page_template( 'template_wepay_order_query_page.php' )) {

        $template = WCC_WEPAY_PLUGIN_PATH . 'inc/class-wc-gateway-wepay-order-query.php';
    }

    return $template;
}


class WCC_Wepay
{
    static private $instance = null;

    private function __construct() {
        add_action('plugins_loaded', array($this, 'init'));
    }
    
    private function __wakeup() {
        
    }
    
    private function __clone() {
        
    }
    
    static public function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function init() {
        // 判断wcc是否启用，如果未启用，后续更多代码就不执行
        if ( !in_array('woocommerce/woocommerce.php', get_option('active_plugins') ) ) {
            return;
        }
        
        // 定义微信网关的核心类
        include WCC_WEPAY_PLUGIN_PATH.'inc/class-wc-gateway-wepay.php';

        // 引入其他的必要的文件
        include WCC_WEPAY_PLUGIN_PATH.'inc/class-wc-gateway-wepay-response.php';
        include WCC_WEPAY_PLUGIN_PATH.'inc/class-wc-gateway-wepay-request.php';
        include WCC_WEPAY_PLUGIN_PATH.'inc/class-wc-gateway-wepay-notify.php';
        
        // 告诉wcc你添加了一个新的网关
        add_filter('woocommerce_payment_gateways', array($this, 'add_gatewawy'));
    }
   
    
    public function add_gatewawy($methods) {
        $methods[] = 'WC_Gateway_Wepay';        
        return $methods;
    }
    
}

WCC_Wepay::get_instance();



