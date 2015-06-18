<?php
/**
 * @copyright 2014
 * @description: config.config
 * @file: c.c.php
 * @charset: UTF-8
 * @create: 2014-10-14
 * @version 1.0
**/

!defined('SYSTEM') && die('ERROR-C_C');
$CFG = array();
/* LOG */
$CFG['LOG_DATAURL'] = SYSTEM_DATA.'log/';
/* SMARTY */
$CFG['SMARTY_COMPILE_CHECK'] = TRUE;
$CFG['SMARTY_DEBUG']         = FALSE;
$CFG['SMARTY_CACHE']         = FALSE;
$CFG['SMARTY_USE_SUB_DIRS']  = TRUE;
$CFG['SMARTY_CACHE_LIFE_TIME'] = 300;
$CFG['SMARTY_LEFT_DELI']       = '<{';
$CFG['SMARTY_RIGHT_DELI']      = '}>';
$CFG['SMARTY_DATA_CONFIG']     = SYSTEM_DATA.'smarty/config/';
$CFG['SMARTY_DATA_CACHE']      = SYSTEM_DATA.'smarty/cache/';
$CFG['SMARTY_TPL']             = SYSTEM_TPL.'default/';
$CFG['SMARTY_DATA_TPL_C']      = SYSTEM_DATA.'smarty/tpl_c/';
/* SESSION */
$CFG['SESS_TYPE']        = 'MYSQLI';
$CFG['SESS_PATH']        = NULL;
$CFG['SESS_NAME']        = 'PHPSESSID';
$CFG['SESS_TABLE_NAME']  = 'session';
$CFG['SESS_ID_NAME']     = 'sess_id';
$CFG['SESS_DATA_NAME']   = 'sess_data';
$CFG['SESS_TIME_NAME']   = 'sess_time';
$CFG['SESS_IP_NAME']     = 'sess_ip';
$CFG['SESS_U_ID_NAME']   = 'sess_u_id';
$CFG['SESS_U_NAME']      = 'u_id';
$CFG['MAX_LIFE_TIME']    = 1440;
$CFG['SESS_MYSQLI_MARK'] = 'session_mysqli';
$CFG['SESS_MYSQLI_CONF']['MYSQLI_HOST']   = '127.0.0.1';
$CFG['SESS_MYSQLI_CONF']['MYSQLI_USER']   = 'root';
$CFG['SESS_MYSQLI_CONF']['MYSQLI_PASS']   = 'root';
$CFG['SESS_MYSQLI_CONF']['MYSQLI_PORT']   = 3306;
$CFG['SESS_MYSQLI_CONF']['MYSQLI_DBNAME'] = 'my_admin';
$CFG['SESS_MYSQLI_CONF']['MYSQLI_TABLE_PREFIX'] = 'ad_';
/* CACHE */
$CFG['CACHE_TYPE'] = 'MEMCACHED';
$CFG['MEMCACHED_DATA']['MEMCACHE_HOST']     = 'localhost';
$CFG['MEMCACHED_DATA']['MEMCACHE_PORT']     = 11211;
$CFG['MEMCACHED_DATA']['MEMCACHE_TIME_OUT'] = 5;
$CFG['MEMCACHED_DATA']['MEMCACHE_EXPIRE']   = 0;
$CFG['MEMCACHED_DATA']['MEMCACHE_FLAG']     = 0;
/* MYSQL 一 */
$CFG['MYSQL']['ADMIN_1']['MYSQLI_HOST'] = 'localhost';
$CFG['MYSQL']['ADMIN_1']['MYSQLI_USER'] = 'root';
$CFG['MYSQL']['ADMIN_1']['MYSQLI_PASS'] = 'root';
$CFG['MYSQL']['ADMIN_1']['MYSQLI_PORT'] = 3306;
$CFG['MYSQL']['ADMIN_1']['MYSQLI_DBNAME']  = 'my_admin';
$CFG['MYSQL']['ADMIN_1']['MYSQLI_CHARSET'] = 'utf8';
$CFG['MYSQL']['ADMIN_1']['MYSQLI_TABLE_PREFIX'] = 'ad_';
/* MYSQL 二 */
$CFG['MYSQL']['ADMIN']['MYSQLI_ISCACHE']    = FALSE;    /* 是否缓存 */
$CFG['MYSQL']['ADMIN']['MYSQLI_CACHE_TIME'] = 1200;
$CFG['MYSQL']['ADMIN']['MYSQLI_ISRW']       = TRUE;    /* 是否读写分离 */
$CFG['MYSQL']['ADMIN']['MYSQLI_CACHE']['CACHE_TYPE'] = 'MEMCACHED';
$CFG['MYSQL']['ADMIN']['MYSQLI_CACHE']['MEMCACHED_DATA']['MEMCACHE_HOST']     = 'localhost';
$CFG['MYSQL']['ADMIN']['MYSQLI_CACHE']['MEMCACHED_DATA']['MEMCACHE_PORT']     = 11211;
$CFG['MYSQL']['ADMIN']['MYSQLI_CACHE']['MEMCACHED_DATA']['MEMCACHE_TIME_OUT'] = 5;
$CFG['MYSQL']['ADMIN']['MYSQLI_CACHE']['MEMCACHED_DATA']['MEMCACHE_EXPIRE']   = 0;
$CFG['MYSQL']['ADMIN']['MYSQLI_CACHE']['MEMCACHED_DATA']['MEMCACHE_FLAG']     = 0;
$CFG['MYSQL']['ADMIN']['MYSQLI_WRITE']['MYSQLI_HOST']    = 'localhost';    /* 可写帐号 */
$CFG['MYSQL']['ADMIN']['MYSQLI_WRITE']['MYSQLI_USER']    = 'root';
$CFG['MYSQL']['ADMIN']['MYSQLI_WRITE']['MYSQLI_PASS']    = 'root';
$CFG['MYSQL']['ADMIN']['MYSQLI_WRITE']['MYSQLI_DBNAME']  = 'my_admin';
$CFG['MYSQL']['ADMIN']['MYSQLI_WRITE']['MYSQLI_PORT']    = 3306;
$CFG['MYSQL']['ADMIN']['MYSQLI_WRITE']['MYSQLI_CHARSET'] = 'my_admin';
$CFG['MYSQL']['ADMIN']['MYSQLI_WRITE']['MYSQLI_TABLE_PREFIX'] = 'ad_';
$CFG['MYSQL']['ADMIN']['MYSQLI_READ']['MYSQLI_HOST']    = 'localhost';    /* 只读帐号 */
$CFG['MYSQL']['ADMIN']['MYSQLI_READ']['MYSQLI_USER']    = 'root';
$CFG['MYSQL']['ADMIN']['MYSQLI_READ']['MYSQLI_PASS']    = 'root';
$CFG['MYSQL']['ADMIN']['MYSQLI_READ']['MYSQLI_PORT']    = 3306;
$CFG['MYSQL']['ADMIN']['MYSQLI_READ']['MYSQLI_DBNAME']  = 'my_admin';
$CFG['MYSQL']['ADMIN']['MYSQLI_READ']['MYSQLI_CHARSET'] = 'utf8';
$CFG['MYSQL']['ADMIN']['MYSQLI_READ']['MYSQLI_TABLE_PREFIX'] = 'ad_';
?>