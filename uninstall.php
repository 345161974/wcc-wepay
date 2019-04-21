<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

function delete_wepay_qrcode_page() {

        $page_id = -1;

        // 设置付款二维码页面
        $author_id = 1;
        $slug = 'wepay-page';
        $title = 'Wepay_Qrcode_Page';

        $wepay_page = get_page_by_title( $title );
        // 检查该页面是否存在,如果不存在就新建
        if ($wepay_page) {
            
            //file_put_contents(WCC_WEPAY_PLUGIN_PATH.'uninstall.txt', "delete_wepay_qrcode_page() $wepay_page->ID():".$wepay_page->ID.PHP_EOL, FILE_APPEND);

            // 此处要记录$post_id的值,此值为: www.xxx.com/?page_id=$post_id 未来显示二维码页面
            wp_delete_post($wepay_page->ID, true);
        }

}

function delete_wepay_order_query_page() {

        $page_id = -1;

        // 设置付款二维码页面
        $author_id = 1;
        $slug = 'wepay-page';
        $title = 'Wepay_Order_Query_Page';

        $wepay_page = get_page_by_title( $title );
        // 检查该页面是否存在,如果不存在就新建
        if ($wepay_page) {
            
            //file_put_contents(WCC_WEPAY_PLUGIN_PATH.'uninstall.txt', "delete_wepay_order_query_page() $wepay_page->ID():".$wepay_page->ID.PHP_EOL, FILE_APPEND);

            // 此处要记录$post_id的值,此值为: www.xxx.com/?page_id=$post_id 未来显示二维码页面
            wp_delete_post($wepay_page->ID, true);
        }

}

// 删除二维码页面
delete_wepay_qrcode_page();

// 删除订单查询页面
delete_wepay_order_query_page();

// 删除生成二维码页面option
$option_name1 = 'wepay_qrcode_url';
delete_option($option_name1);

// 删除订单查询页面option
$option_name2 = 'wepay_order_query_url';
delete_option($option_name2);



