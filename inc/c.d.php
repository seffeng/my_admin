<?php
/**
 * @copyright 2014
 * @description: config.define
 * @file: c.d.php
 * @charset: UTF-8
 * @create: 2014-10-14
 * @version 1.0
**/

!defined('SYSTEM') && die('ERROR-C_D');
define('SYSTEM_THIS_TIME',          CORE_THIS_TIME);        /*当前时间戳*/
define('SYSTEM_THIS_IPS',           CORE_THIS_IPS);         /*客户端IP*/
define('SYSTEM_THIS_IP',            CORE_THIS_IP);          /*客户端IP_INT*/
define('SYSTEM_HTTP_DOMAIN',        $_SERVER['SERVER_NAME']);     /*HTTP域*/
define('SYSTEM_SESS_DOMAIN',        SYSTEM_HTTP_DOMAIN);          /*SESS域*/
define('SYSTEM_CHARSET',            'UTF-8');               /*charset[default-UTF-8]*/
define('SESS_AUTO_START',           ini_get('session.auto_start'));    /* session.auto_start */
!SESS_AUTO_START && ini_set('session.save_handler',     'user');       /* session_user */
ini_set('session.cookie_domain',    SYSTEM_SESS_DOMAIN);    /* session_domain */
define('WEB_URL',                   'http://'.SYSTEM_HTTP_DOMAIN.'/');
define('SUPER_ADMIN_ID',            '1');       /* 管理员ID */
?>