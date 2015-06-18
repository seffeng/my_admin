<?php
/**
 * @copyright 2015
 * @description: login
 * @file: login.php
 * @charset: UTF-8
 * @create: 2015-5-28
 * @version 1.0
**/

!defined('SYSTEM') && die('ERROR-SYS_LOGIN');
switch($get_data['action']){
    /*登录*/
    case 'login' : {
        $username = get_var_value('username');
        $userpass = get_var_value('userpass');
        $return = get_init('admin') -> login($username, $userpass);
        if($return['r'] == 1) $return['u'] = ADMIN_FILE;
        die(json_encode($return));
        break;
    }
    /*退出*/
    case 'logout' : {
        get_init('admin') -> logout();
        to_url(ADMIN_FILE);
        break;
    }
    /*页面显示*/
    default : {
        get_mod('smarty') -> show($get_data['mod'].'/login.html');
    }
}
?>