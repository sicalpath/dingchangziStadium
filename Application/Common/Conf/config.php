<?php
return array(
	//'配置项'=>'配置值'
	'DEFAULT_MODULE'     => 'Home',
    'MODULE_DENY_LIST'   => array('Common'),
    'SHOW_PAGE_TRACE' =>true, 
    'S_PAGE' => 12,
    'O_PAGE' => 12,
    'DEFAULT_CHARSET'       =>  'utf-8',
    
    //session配置
    'NOWTIME' => time(),

    //'SESSION_OPTIONS' => array('path'=>'D:/php/sessions/'),

     /* 数据库配置 */
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'localhost', // 服务器地址
    'DB_NAME'   => 'dcz', // 数据库名
    'DB_USER'   => 'dcz', // 用户名
    'DB_PWD'    => '*',  // 密码
    'DB_PORT'   => '3306', // 端口
    'DB_PREFIX' => 'dcz_', // 数据库表前缀

     /* 全局过滤配置 */
    'DEFAULT_FILTER' => 'strip_tags,stripslashes,htmlspecialchars', //全局过滤函数

        /*支付宝即时到帐*/
    'alipay_partner' => '*',
    'alipay_key' => '*',
    'alipay_seller_email' => '*',
    'alipay_transport' =>   'http',
    'alipay_cacert' =>  getcwd().'\\cacert.pem',
);