# wcc-wepay

woocommerce wepay plugin, woocommerce微信支付插件

### 重要的事情说三遍：
***本项目代码仅供参考学习!***

***本项目代码仅供参考学习!***

***本项目代码仅供参考学习!***

### 插件已支持功能介绍(2019.04.21更新)

> 增加插件的清理工作:uninstall.php

> 浏览器兼容性测试测试，支持Chrome,Firefox,IE 11

### 插件已支持功能介绍(2018.12.18更新)

> 支持最基本的PC扫码支付

> 支付完成自动跳转(微信demo演示并未提供该参数:return_url,需自己实现该功能...支付宝是自带了自动跳转return_url的参数)

### 插件未完善功能说明(2018.12.18更新)

> 插件的一些清理工作(关闭/启用/删除插件)暂时还未开发完成(待开发)

> 浏览器兼容性测试未测试，目前支持Chrome,Firefox


## 运行环境(2018.12.16更新)

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

