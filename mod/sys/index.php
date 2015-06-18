<?php
/**
 * @copyright 2015
 * @description: index
 * @file: index.php
 * @charset: UTF-8
 * @version 1.0
**/

!defined('SYSTEM') && die('ERROR-SYS_INDEX');
switch($get_data['action']){
    case 'index' : {
        $smarty = get_mod('smarty');
        $smarty -> assign('nav', explode(',', '后台管理,默认首页'));   //设定导航
        $return = array('r' => 1, 'd' => array('content' => $smarty -> fetch($get_data['mod'].'/index.html')), 'm' => 'success');
        die(json_encode($return));
        break;
    }
    default : {
        get_mod('smarty') -> show('index.html');
    }
}
?>