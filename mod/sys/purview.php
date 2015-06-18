<?php
/**
 * @copyright 2015
 * @description: 权限
 * @file: purview.php
 * @author: Zhang Xue Feng
 * @charset: UTF-8
 * @time: 2015-04-24
 * @version 1.0
**/

!defined('SYSTEM') && die('ERROR-SYS_PURVIEW');
$return = array('r' => 0, 'm' => '失败,请稍后重试！', 'd' => array());
$smarty = get_mod('smarty');
switch($get_data['action']){
    case 'add' : {  /*权限添加*/
        if($get_data['o'] == 'save'){
            $form_data = array();
            $form_data['pv_name'] = get_var_value('pv_name');
            $form_data['pv_key'] = get_var_value('pv_key');
            $form_data['pv_status'] = get_var_value('pv_status');
            if($form_data['pv_name'] == ''){
                $return = array('r' => 0, 'm' => '错误:名称为空', 'd' => NULL);
            }elseif($form_data['pv_key'] == ''){
                $return = array('r' => 0, 'm' => '错误:权限为空', 'd' => NULL);
            }elseif(!in_array($form_data['pv_status'], array(1, 2))){
                $return = array('r' => 0, 'm' => '状态值不在范围', 'd' => NULL);
            }else{
                $purview = get_init('purview') -> get_purview(array(
                    'WHERE' => ' AND pv_name=\''.$form_data['pv_name'].'\' AND pv_isdel=0',
                    'ISONE' => TRUE,
                ));
                if($purview['r'] == 1){
                    $return = array('r' => 0, 'm' => '错误:名称已存在', 'd' => NULL);
                }else{
                    $form_data['pv_lasttime'] = SYSTEM_THIS_TIME;
                    $form_data['pv_lastip']   = SYSTEM_THIS_IP;
                    $result = get_init('purview') -> purview_add($form_data);
                    if($result){
                        $return = array('r' => 1, 'm' => '权限添加成功', 'd' => NULL);
                    }else{
                        $return = array('r' => 0, 'm' => '权限添加失败', 'd' => NULL);
                    }
                }
            }
        }else{
            $return['d']['nav'] = explode(',', '后台管理,权限管理,添加权限');  //设定导航
            $return['d']['nav_adm'] = array('adm' => 'list', 'name' =>'权限列表');  //设定导航
            $return['d']['content'] = $smarty -> fetch($get_data['mod'].'/purview_add.html');
            $return['r'] = 1;
            $return['m'] = '';
        }
        break;
    }
    case 'edit' : {  /*权限编辑*/
        if($get_data['o'] == 'save'){
            $form_data = $info_data = array();
            $id = get_var_value('id');
            $form_data['pv_name'] = get_var_value('pv_name');
            $form_data['pv_key'] = get_var_value('pv_key');
            $form_data['pv_status'] = get_var_value('pv_status');
            if($id < 1){
                $return = array('r' => 0, 'm' => 'ID错误', 'd' => NULL);
            }elseif($form_data['pv_name'] == ''){
                $return = array('r' => 0, 'm' => '错误:组名为空', 'd' => NULL);
            }elseif($form_data['pv_key'] == ''){
                $return = array('r' => 0, 'm' => '错误:权限为空', 'd' => NULL);
            }elseif(!in_array($form_data['pv_status'], array(1, 2))){
                $return = array('r' => 0, 'm' => '状态值不在范围', 'd' => NULL);
            }else{
                $form_data['pv_lasttime'] = SYSTEM_THIS_TIME;
                $form_data['pv_lastip']   = SYSTEM_THIS_IP;
                $result = get_init('purview') -> purview_edit($form_data, $id);
                if($result){
                    $return = array('r' => 1, 'm' => '权限修改成功', 'd' => NULL);
                }else{
                    $return = array('r' => 0, 'm' => '权限修改失败', 'd' => NULL);
                }
            }
        }else{
            $id = get_var_value('id');
            $purview = get_init('purview') -> get_purview_byid($id);
            $smarty -> assign('purview', $purview);
            $return['d']['nav'] = explode(',', '后台管理,权限管理,修改权限');  //设定导航
            $return['d']['nav_adm'] = array('adm' => 'list', 'name' =>'权限列表');  //设定导航
            $return['d']['content'] = $smarty -> fetch($get_data['mod'].'/purview_edit.html');
            $return['r'] = 1;
            $return['m'] = '';
        }
        break;
    }
    case 'del' : {  /*权限删除*/
        $id = get_var_value('id');
        $return = get_init('purview') -> purview_del($id);
        break;
    }
    default : { /*列表显示*/
        $pg = get_var_value('pg');
        $pg_size = 15;
        if($pg < 1) $pg = 1;
        $param = array(
            'WHERE' => ' AND pv_isdel=0',
            'ORDER' => ' pv_id DESC',
            'LIMIT' => (($pg-1)*$pg_size).','.$pg_size,
            'COUNT' => TRUE,
        );
        $purview = get_init('purview') -> get_purview($param);
        $smarty -> assign('list', $purview['d']);
        $smarty -> assign('page', get_page($purview['n'], $pg, $pg_size));
        $return['d']['nav'] = explode(',', '后台管理,权限管理,列表');  //设定导航
        $return['d']['nav_adm'] = array('adm' => 'add', 'name' =>'添加权限');  //设定导航
        $return['d']['content'] = $smarty -> fetch($get_data['mod'].'/purview.html');
        $return['r'] = 1;
        $return['m'] = 'success';
    }
}
die(json_encode($return));
?>