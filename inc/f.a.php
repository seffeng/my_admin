<?php
/**
 * @copyright 2014
 * @description: function.application
 * @file: f.a.php
 * @charset: UTF-8
 * @create: 2014-10-14
 * @version 1.0
**/

!defined('SYSTEM') && die('ERROR-F_A');
/**
 * @name: to_url
 * @description: url跳转
 * @param: string url地址
 * @return: void
**/
function to_url($url='/'){
    header('LOCATION:'.$url);
    exit;
}
?>