<?php
/**
 * @copyright 2015
 * @description: 管理员组
 * @file: admin_group_group.php
 * @author: Zhang Xue Feng
 * @charset: UTF-8
 * @time: 2015-04-24
 * @version 1.0
**/

!defined('SYSTEM') && die('ERROR-SYS_admin_group_GROUP');
$return = array('r' => 0, 'm' => '失败,请稍后重试！', 'd' => array());
$smarty = get_mod('smarty');
switch($get_data['action']){
    case 'add' : {  /*管理员组添加*/
        if($get_data['o'] == 'save'){
            $form_data = array();
            $form_data['adg_name'] = get_var_value('adg_name');
            $form_data['adg_status'] = get_var_value('adg_status');
            if($form_data['adg_name'] == ''){
                $return = array('r' => 0, 'm' => '错误:组名为空', 'd' => NULL);
            }elseif(!in_array($form_data['adg_status'], array(1, 2))){
                $return = array('r' => 0, 'm' => '状态值不在范围', 'd' => NULL);
            }else{
                $admin_group = get_init('admin_group') -> get_admin_group(array(
                    'WHERE' => ' AND adg_name=\''.$form_data['adg_name'].'\' AND adg_isdel=0',
                    'ISONE' => TRUE,
                ));
                if($admin_group['r'] == 1){
                    $return = array('r' => 0, 'm' => '错误:该组名已存在', 'd' => NULL);
                }else{
                    $form_data['adg_lasttime'] = SYSTEM_THIS_TIME;
                    $form_data['adg_lastip']   = SYSTEM_THIS_IP;
                    $result = get_init('admin_group') -> admin_group_add($form_data);
                    if($result){
                        $return = array('r' => 1, 'm' => '管理员组添加成功', 'd' => NULL);
                    }else{
                        $return = array('r' => 0, 'm' => '管理员组添加失败', 'd' => NULL);
                    }
                }
            }
        }else{
            $return['d']['nav'] = explode(',', '后台管理,管理员组,添加管理员组');  //设定导航
            $return['d']['nav_adm'] = array('adm' => 'list', 'name' =>'管理员组列表');  //设定导航
            $return['d']['content'] = $smarty -> fetch($get_data['mod'].'/admin_group_add.html');
            $return['r'] = 1;
            $return['m'] = '';
        }
        break;
    }
    case 'edit' : {  /*管理员组编辑*/
        if($get_data['o'] == 'save'){
            $form_data = $info_data = array();
            $id = get_var_value('id');
            $form_data['adg_name'] = get_var_value('adg_name');
            $form_data['adg_status'] = get_var_value('adg_status');
            if($id < 1){
                $return = array('r' => 0, 'm' => 'ID错误', 'd' => NULL);
            }elseif($form_data['adg_name'] == ''){
                $return = array('r' => 0, 'm' => '错误:组名为空', 'd' => NULL);
            }elseif(!in_array($form_data['adg_status'], array(1, 2))){
                $return = array('r' => 0, 'm' => '状态值不在范围', 'd' => NULL);
            }else{
                $form_data['adg_lasttime'] = SYSTEM_THIS_TIME;
                $form_data['adg_lastip']   = SYSTEM_THIS_IP;
                $result = get_init('admin_group') -> admin_group_edit($form_data, $id);
                if($result){
                    $return = array('r' => 1, 'm' => '管理员组修改成功', 'd' => NULL);
                }else{
                    $return = array('r' => 0, 'm' => '管理员组修改失败', 'd' => NULL);
                }
            }
        }else{
            $id = get_var_value('id');
            $admin_group = get_init('admin_group') -> get_admin_group_byid($id);
            $smarty -> assign('admin_group', $admin_group);
            $return['d']['nav'] = explode(',', '后台管理,管理员组,修改管理员组');  //设定导航
            $return['d']['nav_adm'] = array('adm' => 'list', 'name' =>'管理员组列表');  //设定导航
            $return['d']['content'] = $smarty -> fetch($get_data['mod'].'/admin_group_edit.html');
            $return['r'] = 1;
            $return['m'] = '';
        }
        break;
    }
    case 'del' : {  /*管理员组删除*/
        $id = get_var_value('id');
        $return = get_init('admin_group') -> admin_group_del($id);
        break;
    }
    default : { /*列表显示*/
        $pg = get_var_value('pg');
        $pg_size = 15;
        if($pg < 1) $pg = 1;
        $param = array(
            'WHERE' => ' AND adg_isdel=0',
            'ORDER' => ' adg_id DESC',
            'LIMIT' => (($pg-1)*$pg_size).','.$pg_size,
            'COUNT' => TRUE,
        );
        $admin_group = get_init('admin_group') -> get_admin_group($param);
        $smarty -> assign('list', $admin_group['d']);
        $smarty -> assign('page', get_page($admin_group['n'], $pg, $pg_size));
        $return['d']['nav'] = explode(',', '后台管理,管理员组,管理员组列表');  //设定导航
        $return['d']['nav_adm'] = array('adm' => 'add', 'name' =>'添加管理员组');  //设定导航
        $return['d']['content'] = $smarty -> fetch($get_data['mod'].'/admin_group.html');
        $return['r'] = 1;
        $return['m'] = 'success';
    }
}
die(json_encode($return));
?>