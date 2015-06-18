<?php
/**
 * @copyright 2015
 * @description: 权限
 * @file: purview_group.php
 * @author: Zhang Xue Feng
 * @charset: UTF-8
 * @time: 2015-04-24
 * @version 1.0
**/

!defined('SYSTEM') && die('ERROR-SYS_PURVIEW_GROUP');
$return = array('r' => 0, 'm' => '失败,请稍后重试！', 'd' => array());
$smarty = get_mod('smarty');
switch($get_data['action']){
    case 'add' : {  /*权限添加*/
        if($get_data['o'] == 'save'){
            $form_data = array();
            $form_data['pvg_name'] = get_var_value('pvg_name');
            $form_data['pvg_status'] = get_var_value('pvg_status');
            $form_data['pv_ids'] = get_var_value('pv_ids');
            if($form_data['pv_ids'] != ''){
                $form_data['pv_ids'] = ','.$form_data['pv_ids'];
            }
            if($form_data['pvg_name'] == ''){
                $return = array('r' => 0, 'm' => '错误:名称为空', 'd' => NULL);
            }elseif(!in_array($form_data['pvg_status'], array(1, 2))){
                $return = array('r' => 0, 'm' => '状态值不在范围', 'd' => NULL);
            }else{
                $purview_group = get_init('purview_group') -> get_purview_group(array(
                    'WHERE' => ' AND pvg_name=\''.$form_data['pvg_name'].'\' AND pvg_isdel=0',
                    'ISONE' => TRUE,
                ));
                if($purview_group['r'] == 1){
                    $return = array('r' => 0, 'm' => '错误:名称已存在', 'd' => NULL);
                }else{
                    $form_data['pvg_lasttime'] = SYSTEM_THIS_TIME;
                    $form_data['pvg_lastip']   = SYSTEM_THIS_IP;
                    $result = get_init('purview_group') -> purview_group_add($form_data);
                    if($result){
                        $return = array('r' => 1, 'm' => '权限添加成功', 'd' => NULL);
                    }else{
                        $return = array('r' => 0, 'm' => '权限添加失败', 'd' => NULL);
                    }
                }
            }
        }else{
            $purview = get_init('purview') -> get_purview(array('WHERE' => ' AND pv_status=1 AND pv_isdel=0'));
            $smarty -> assign('purview',  $purview['d']);
            $return['d']['nav'] = explode(',', '后台管理,权限组管理,添加权限组');  //设定导航
            $return['d']['nav_adm'] = array('adm' => 'list', 'name' =>'权限组列表');  //设定导航
            $return['d']['content'] = $smarty -> fetch($get_data['mod'].'/purview_group_add.html');
            $return['r'] = 1;
            $return['m'] = '';
        }
        break;
    }
    case 'edit' : {  /*权限编辑*/
        if($get_data['o'] == 'save'){
            $form_data = $info_data = array();
            $id = get_var_value('id');
            $form_data['pvg_name'] = get_var_value('pvg_name');
            $form_data['pvg_status'] = get_var_value('pvg_status');
            $form_data['pv_ids'] = get_var_value('pv_ids');
            if($form_data['pv_ids'] != ''){
                $form_data['pv_ids'] = ','.$form_data['pv_ids'];
            }
            if($id < 1){
                $return = array('r' => 0, 'm' => 'ID错误', 'd' => NULL);
            }elseif($form_data['pvg_name'] == ''){
                $return = array('r' => 0, 'm' => '错误:组名为空', 'd' => NULL);
            }elseif(!in_array($form_data['pvg_status'], array(1, 2))){
                $return = array('r' => 0, 'm' => '状态值不在范围', 'd' => NULL);
            }else{
                $form_data['pvg_lasttime'] = SYSTEM_THIS_TIME;
                $form_data['pvg_lastip']   = SYSTEM_THIS_IP;
                $result = get_init('purview_group') -> purview_group_edit($form_data, $id);
                if($result){
                    $return = array('r' => 1, 'm' => '权限修改成功', 'd' => NULL);
                }else{
                    $return = array('r' => 0, 'm' => '权限修改失败', 'd' => NULL);
                }
            }
        }else{
            $id = get_var_value('id');
            $purview_group = get_init('purview_group') -> get_purview_group_byid($id);
            $purview = get_init('purview') -> get_purview(array('WHERE' => ' AND pv_status=1 AND pv_isdel=0'));
            $smarty -> assign('purview_group', $purview_group);
            $smarty -> assign('purview',  $purview['d']);
            $smarty -> assign('pv_ids', explode(',', trim($purview_group['pv_ids'], ',')));
            $return['d']['nav'] = explode(',', '后台管理,权限组管理,修改权限组');  //设定导航
            $return['d']['nav_adm'] = array('adm' => 'list', 'name' =>'权限组列表');  //设定导航
            $return['d']['content'] = $smarty -> fetch($get_data['mod'].'/purview_group_edit.html');
            $return['r'] = 1;
            $return['m'] = '';
        }
        break;
    }
    case 'del' : {  /*权限删除*/
        $id = get_var_value('id');
        $return = get_init('purview_group') -> purview_group_del($id);
        break;
    }
    default : { /*列表显示*/
        $pg = get_var_value('pg');
        $pg_size = 15;
        if($pg < 1) $pg = 1;
        $param = array(
            'WHERE' => ' AND pvg_isdel=0',
            'ORDER' => ' pvg_id DESC',
            'LIMIT' => (($pg-1)*$pg_size).','.$pg_size,
            'COUNT' => TRUE,
        );
        $purview_group = get_init('purview_group') -> get_purview_group($param);
        $smarty -> assign('list', $purview_group['d']);
        $smarty -> assign('page', get_page($purview_group['n'], $pg, $pg_size));
        $return['d']['nav'] = explode(',', '后台管理,权限组管理,列表');  //设定导航
        $return['d']['nav_adm'] = array('adm' => 'add', 'name' =>'添加权限组');  //设定导航
        $return['d']['content'] = $smarty -> fetch($get_data['mod'].'/purview_group.html');
        $return['r'] = 1;
        $return['m'] = 'success';
    }
}
die(json_encode($return));
?>