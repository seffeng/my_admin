<?php
/**
 * @copyright 2015
 * @description: 管理员日志
 * @file: admin_log.php
 * @author: Zhang Xue Feng
 * @charset: UTF-8
 * @time: 2015-04-13
 * @version 1.0
**/

!defined('SYSTEM') && die('ERROR-SYS_ADMIN_LOG');
$return = array('r' => 0, 'm' => '失败,请稍后重试！', 'd' => array());
$smarty = get_mod('smarty');
switch($get_data['action']){
    default : { /*列表显示*/
        $pg = get_var_value('pg');
        $pg_size = 15;
        if($pg < 1) $pg = 1;
        $param = array(
            'WHERE' => ' AND al_isdel=0',
            'ORDER' => ' al_id DESC',
            'LIMIT' => (($pg-1)*$pg_size).','.$pg_size,
            'COUNT' => TRUE,
        );
        $admin_log = get_init('admin_log') -> get_admin_log($param);
        if(is_foreach($admin_log['d'])){
            $ad_ids = $admin = $admin_info = array();
            foreach($admin_log['d'] as $val){
                $ad_ids[$val['ad_id']] = $val['ad_id'];
            }
            if(count($ad_ids) > 0){
                $result = get_init('admin') -> get_admin(array(
                    'FIELD' => 'ad_id, ad_username',
                    'WHERE' => ' AND ad_id IN ('.implode(',', $ad_ids).') AND ad_isdel=0',
                ));
                if(is_foreach($result['d'])) foreach($result['d'] as $val){
                    $admin[$val['ad_id']] = $val;
                }
                $result = get_init('admin') -> get_admin_info(array(
                    'FIELD' => 'ad_id, ai_name',
                    'WHERE' => ' AND ad_id IN ('.implode(',', $ad_ids).')',
                ));
                if(is_foreach($result['d'])) foreach($result['d'] as $val){
                    $admin_info[$val['ad_id']] = $val;
                }
            }
            foreach($admin_log['d'] as $key => $val){
                $admin_log['d'][$key]['ai_name'] = isset($admin_info[$val['ad_id']]['ai_name']) ? $admin_info[$val['ad_id']]['ai_name'] : '';
                $admin_log['d'][$key]['ad_username'] = isset($admin[$val['ad_id']]['ad_username']) ? $admin[$val['ad_id']]['ad_username'] : '';
            }
        }
        $smarty -> assign('admin_log', $admin_log['d']);
        $smarty -> assign('page', get_page($admin_log['n'], $pg, $pg_size));
        $return['d']['nav'] = explode(',', '后台管理,管理员日志,日志列表');  //设定导航
        $return['d']['content'] = $smarty -> fetch($get_data['mod'].'/admin_log.html');
        $return['r'] = 1;
        $return['m'] = '';
    }
}
die(json_encode($return));
?>