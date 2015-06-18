<?php
/**
 * @copyright 2015
 * @description: get_menu.php
 * @file: get_menu.php
 * @author: Zhang Xue Feng
 * @charset: UTF-8
 * @time: 2015-04-14
 * @version 1.0
**/

!defined('SYSTEM') && die('ERROR-SYS_GET_MENU');
$menu_nav = get_init('menu_nav') -> get_menu();
die(json_encode($menu_nav));
?>