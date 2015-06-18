<?php
/**
 * @copyright 2015
 * @description: 管理员
 * @file: admin.php
 * @author: Zhang Xue Feng
 * @charset: UTF-8
 * @time: 2015-04-13
 * @version 1.0
**/

!defined('SYSTEM') && die('ERROR-SYS_ADMIN');
$return = array('r' => 0, 'm' => '失败,请稍后重试！', 'd' => array());
$smarty = get_mod('smarty');
switch($get_data['action']){
    case 'add' : {  /*管理员添加*/
        if($get_data['o'] == 'save'){
            $form_data = $info_data = array();
            $form_data['ad_username'] = get_var_value('ad_username');
            $form_data['ad_password'] = get_var_value('ad_password');
            $form_data['ad_status'] = get_var_value('ad_status');
            $form_data['adg_id'] = get_var_value('adg_id');
            $form_data['pv_ids'] = get_var_value('pv_ids');
            $form_data['pvg_ids'] = get_var_value('pvg_ids');
            $info_data['ai_name'] = get_var_value('ai_name');
            $info_data['ai_nickname'] = get_var_value('ai_nickname');
            $info_data['ai_phone'] = get_var_value('ai_phone');
            $info_data['ai_email'] = get_var_value('ai_email');
            if($form_data['pv_ids'] != ''){
                $form_data['pv_ids'] = ','.$form_data['pv_ids'];
            }
            if($form_data['pvg_ids'] != ''){
                $form_data['pvg_ids'] = ','.$form_data['pvg_ids'];
            }
            if($form_data['ad_username'] == ''){
                $return = array('r' => 0, 'm' => '错误:帐号为空', 'd' => NULL);
            }elseif($form_data['ad_password'] == ''){
                $return = array('r' => 0, 'm' => '错误:密码为空', 'd' => NULL);
            }elseif(!in_array($form_data['ad_status'], array(1, 2))){
                $return = array('r' => 0, 'm' => '状态值不在范围', 'd' => NULL);
            }elseif($info_data['ai_phone'] && !check_data($info_data['ai_phone'], 'mobile')){
                $return = array('r' => 0, 'm' => '手机号错误', 'd' => NULL);
            }elseif($info_data['ai_email'] != '' && !check_data($info_data['ai_email'], 'email')){
                $return = array('r' => 0, 'm' => '邮箱错误', 'd' => NULL);
            }else{
                $admin_user = get_init('admin') -> get_admin(array(
                    'WHERE' => ' AND ad_username=\''.$form_data['ad_username'].'\' AND ad_isdel=0',
                    'ISONE' => TRUE,
                ));
                if($admin_user['r'] == 1){
                    $return = array('r' => 0, 'm' => '错误:该帐号已存在', 'd' => NULL);
                }else{
                    $form_data['ad_password'] = get_init('admin') -> en_password($form_data['ad_password']);
                    $form_data['ad_addtime'] = SYSTEM_THIS_TIME;
                    $form_data['ad_addip']   = SYSTEM_THIS_IP;
                    $result = get_init('admin') -> admin_add($form_data);
                    if($result){
                        $info_data['ad_id'] = $result;
                        $info_data['ai_lasttime'] = SYSTEM_THIS_TIME;
                        get_init('admin') -> admin_info_add($info_data);
                        $return = array('r' => 1, 'm' => '管理员添加成功', 'd' => NULL);
                    }else{
                        $return = array('r' => 0, 'm' => '管理员添加失败', 'd' => NULL);
                    }
                }
            }
        }else{
            $adg_list = get_init('admin_group') -> get_admin_group(array('WHERE' => ' AND adg_status=1 AND adg_isdel=0'));
            $purview = get_init('purview') -> get_purview(array('WHERE' => ' AND pv_status=1 AND pv_isdel=0'));
            $purview_group = get_init('purview_group') -> get_purview_group(array('WHERE' => ' AND pvg_status=1 AND pvg_isdel=0'));
            $smarty -> assign('adg_list', $adg_list['d']);
            $smarty -> assign('purview',  $purview['d']);
            $smarty -> assign('purview_group',  $purview_group['d']);
            $return['d']['nav'] = explode(',', '后台管理,管理员,添加管理员');  //设定导航
            $return['d']['nav_adm'] = array('adm' => 'list', 'name' =>'管理员列表');  //设定导航
            $return['d']['content'] = $smarty -> fetch($get_data['mod'].'/admin_add.html');
            $return['r'] = 1;
            $return['m'] = '';
        }
        break;
    }
    case 'edit' : {  /*管理员编辑*/
        if($get_data['o'] == 'save'){
            $form_data = $info_data = array();
            $id = get_var_value('id');
            $form_data['ad_password'] = get_var_value('ad_password');
            $form_data['ad_status'] = get_var_value('ad_status');
            $form_data['adg_id'] = get_var_value('adg_id');
            $info_data['ai_name'] = get_var_value('ai_name');
            $info_data['ai_nickname'] = get_var_value('ai_nickname');
            $info_data['ai_phone'] = get_var_value('ai_phone');
            $info_data['ai_email'] = get_var_value('ai_email');
            $form_data['pv_ids'] = get_var_value('pv_ids');
            $form_data['pvg_ids'] = get_var_value('pvg_ids');
            if($form_data['pv_ids'] != ''){
                $form_data['pv_ids'] = ','.$form_data['pv_ids'];
            }
            if($form_data['pvg_ids'] != ''){
                $form_data['pvg_ids'] = ','.$form_data['pvg_ids'];
            }
            if($id < 1){
                $return = array('r' => 0, 'm' => 'ID错误', 'd' => NULL);
            }elseif(!in_array($form_data['ad_status'], array(1, 2))){
                $return = array('r' => 0, 'm' => '状态值不在范围', 'd' => NULL);
            }elseif($info_data['ai_phone'] && !check_data($info_data['ai_phone'], 'mobile')){
                $return = array('r' => 0, 'm' => '手机号错误', 'd' => NULL);
            }elseif($info_data['ai_email'] != '' && !check_data($info_data['ai_email'], 'email')){
                $return = array('r' => 0, 'm' => '邮箱错误', 'd' => NULL);
            }else{
                if($form_data['ad_password'] == ''){
                    unset($form_data['ad_password']);
                }else{
                    $form_data['ad_password'] = get_init('admin') -> en_password($form_data['ad_password']);
                }
                $form_data['ad_lasttime'] = SYSTEM_THIS_TIME;
                $form_data['ad_lastip']   = SYSTEM_THIS_IP;
                $result = get_init('admin') -> admin_edit($form_data, $id);
                $info_data['ai_lasttime'] = SYSTEM_THIS_TIME;
                get_init('admin') -> admin_info_edit($info_data, $id);
                if($result){
                    $return = array('r' => 1, 'm' => '管理员修改成功', 'd' => NULL);
                }else{
                    $return = array('r' => 0, 'm' => '管理员修改失败', 'd' => NULL);
                }
            }
        }else{
            $ad_id = get_var_value('id');
            $admin = get_init('admin') -> get_admin_byid($ad_id);
            $smarty -> assign('admin', $admin);
            $adg_list = get_init('admin_group') -> get_admin_group(array('WHERE' => ' AND adg_status=1 AND adg_isdel=0'));
            $purview = get_init('purview') -> get_purview(array('WHERE' => ' AND pv_status=1 AND pv_isdel=0'));
            $purview_group = get_init('purview_group') -> get_purview_group(array('WHERE' => ' AND pvg_status=1 AND pvg_isdel=0'));
            $smarty -> assign('adg_list', $adg_list['d']);
            $smarty -> assign('purview',  $purview['d']);
            $smarty -> assign('purview_group',  $purview_group['d']);
            $smarty -> assign('admin_pv',  explode(',', trim($admin['pv_ids'], ',')));
            $smarty -> assign('admin_pvg', explode(',', trim($admin['pvg_ids'], ',')));
            $return['d']['nav'] = explode(',', '后台管理,管理员,修改管理员');  //设定导航
            $return['d']['nav_adm'] = array('adm' => 'list', 'name' =>'管理员列表');  //设定导航
            $return['d']['content'] = $smarty -> fetch($get_data['mod'].'/admin_edit.html');
            $return['r'] = 1;
            $return['m'] = '';
        }
        break;
    }
    case 'del' : {  /*管理员删除*/
        $id = get_var_value('id');
        $return = get_init('admin') -> del_admin($id);
        break;
    }
    default : { /*列表显示*/
        $pg = get_var_value('pg');
        $pg_size = 15;
        if($pg < 1) $pg = 1;
        $admin_param = array(
            'WHERE' => ' AND ad_isdel=0',
            'ORDER' => ' ad_id DESC',
            'LIMIT' => (($pg-1)*$pg_size).','.$pg_size,
            'COUNT' => TRUE,
        );
        $admin = get_init('admin') -> get_admin($admin_param);
        $admin_list = $admin_info = $ad_ids = $adg_ids = $admin_group = array();
        if(is_foreach($admin['d'])){
            foreach($admin['d'] as $val){
                $ad_ids[$val['ad_id']] = $val['ad_id'];
                $adg_ids[$val['adg_id']] = $val['adg_id'];
            }
            $result = get_init('admin') -> get_admin_info(array('WHERE' => ' AND ad_id IN ('.implode(',', $ad_ids).')'));
            if(is_foreach($result['d'])) foreach($result['d'] as $val){
                $admin_info[$val['ad_id']] = $val;
            }
            $result = get_init('admin_group') -> get_admin_group(array('WHERE' => ' AND adg_id IN ('.implode(',', $adg_ids).') AND adg_isdel=0'));
            if(is_foreach($result['d'])) foreach($result['d'] as $val){
                $admin_group[$val['adg_id']] = $val;
            }
            foreach($admin['d'] as $val){
                $admin_list[$val['ad_id']] = $val;
                $admin_list[$val['ad_id']]['adg_name'] = isset($admin_group[$val['adg_id']]['adg_name']) ? $admin_group[$val['adg_id']]['adg_name'] : '';
                $admin_list[$val['ad_id']]['ai_name'] = isset($admin_info[$val['ad_id']]['ai_name']) ? $admin_info[$val['ad_id']]['ai_name'] : '';
                $admin_list[$val['ad_id']]['ai_phone'] = (isset($admin_info[$val['ad_id']]['ai_phone']) && $admin_info[$val['ad_id']]['ai_phone'] != '0') ? $admin_info[$val['ad_id']]['ai_phone'] : '';
                $admin_list[$val['ad_id']]['ai_email'] = isset($admin_info[$val['ad_id']]['ai_email']) ? $admin_info[$val['ad_id']]['ai_email'] : '';
            }
        }
        $smarty -> assign('admin', $admin_list);
        $smarty -> assign('page', get_page($admin['n'], $pg, $pg_size));
        $return['d']['nav'] = explode(',', '后台管理,管理员,列表');  //设定导航
        $return['d']['nav_adm'] = array('adm' => 'add', 'name' =>'添加管理员');  //设定导航
        $return['d']['content'] = $smarty -> fetch($get_data['mod'].'/admin.html');
        $return['r'] = 1;
        $return['m'] = 'success';
    }
}
die(json_encode($return));
?>