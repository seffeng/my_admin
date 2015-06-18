<?php
/**
 * @copyright 2015
 * @description: 导航
 * @file: menu_nav.php
 * @author: Zhang Xue Feng
 * @charset: UTF-8
 * @time: 2015-04-15
 * @version 1.0
**/

!defined('SYSTEM') && die('ERROR-SYS_MENU_NAV');
$return = array('r' => 0, 'm' => '失败,请稍后重试！', 'd' => array());
$smarty = get_mod('smarty');
switch($get_data['action']){
    case 'add' : {  /*添加导航*/
        if($get_data['o'] == 'save'){
            $form_data = array();
            $form_data['mn_name'] = get_var_value('mn_name');
            $form_data['mn_url']  = get_var_value('mn_url');
            $form_data['mn_type'] = get_var_value('mn_type');
            $form_data['mn_pid']  = get_var_value('mn_pid');
            $form_data['mn_sort'] = get_var_value('mn_sort');
            $form_data['mn_status'] = get_var_value('mn_status');
            $form_data['mn_icon'] = get_var_value('mn_icon');
            if($form_data['mn_name'] == ''){
                $return = array('r' => 0, 'm' => '错误:菜单名称为空', 'd' => NULL);
            }elseif($form_data['mn_url'] == ''){
                $return = array('r' => 0, 'm' => '错误:菜单地址为空', 'd' => NULL);
            }elseif(!in_array($form_data['mn_status'], array(1, 2))){
                $return = array('r' => 0, 'm' => '菜单状态值不在范围', 'd' => NULL);
            }else{
                $form_data['mn_lasttime'] = SYSTEM_THIS_TIME;
                $form_data['mn_lastip']   = SYSTEM_THIS_IP;
                $result = get_init('menu_nav') -> menu_nav_add($form_data);
                if($result){
                    $return = array('r' => 1, 'm' => '菜单添加成功', 'd' => NULL);
                }else{
                    $return = array('r' => 0, 'm' => '菜单添加失败', 'd' => NULL);
                }
            }
        }else{
            $menu_nav = get_init('menu_nav') -> get_menu_relation();
            $menu_html = get_init('menu_nav') -> menu_arraytohtml($menu_nav);
            $smarty -> assign('mn_type', array('其他', '左导航', '上导航'));   //设定导航
            $smarty -> assign('menu_html', $menu_html);
            $return['d']['nav'] = explode(',', '后台管理,导航菜单,添加导航');  //设定导航
            $return['d']['nav_adm'] = array('adm' => 'list', 'name' =>'导航列表');  //设定导航
            $return['d']['content'] = $smarty -> fetch($get_data['mod'].'/menu_nav_add.html');
            $return['r'] = 1;
            $return['m'] = '';
        }
        break;
    }
    case 'edit' : {  /*编辑导航*/
        if($get_data['o'] == 'save'){
            $form_data = array();
            $id = get_var_value('mn_id');
            $form_data['mn_name'] = get_var_value('mn_name');
            $form_data['mn_url']  = get_var_value('mn_url');
            $form_data['mn_type'] = get_var_value('mn_type');
            $form_data['mn_pid']  = get_var_value('mn_pid');
            $form_data['mn_sort'] = get_var_value('mn_sort');
            $form_data['mn_status'] = get_var_value('mn_status');
            $form_data['mn_icon'] = get_var_value('mn_icon');
            if($id < 0){
                $return = array('r' => 0, 'm' => 'ID错误', 'd' => NULL);
            }elseif($form_data['mn_name'] == ''){
                $return = array('r' => 0, 'm' => '错误:菜单名称为空', 'd' => NULL);
            }elseif($form_data['mn_url'] == ''){
                $return = array('r' => 0, 'm' => '错误:菜单地址为空', 'd' => NULL);
            }elseif(!in_array($form_data['mn_status'], array(1, 2))){
                $return = array('r' => 0, 'm' => '菜单状态值不在范围', 'd' => NULL);
            }else{
                $form_data['mn_lasttime'] = SYSTEM_THIS_TIME;
                $form_data['mn_lastip']   = SYSTEM_THIS_IP;
                $result = get_init('menu_nav') -> menu_nav_edit($form_data, $id);
                if($result){
                    $return = array('r' => 1, 'm' => '菜单修改成功', 'd' => NULL);
                }else{
                    $return = array('r' => 0, 'm' => '菜单修改失败', 'd' => NULL);
                }
            }
        }else{
            $id = get_var_value('id');
            $menu_list = get_init('menu_nav') -> get_menu_nav(array(
                'WHERE' => ' AND mn_id=\''.$id.'\'',
                'ISONE' => TRUE,
            ));
            $menu_nav = get_init('menu_nav') -> get_menu_relation();
            $menu_html = get_init('menu_nav') -> menu_arraytohtml($menu_nav, 0, isset($menu_list['d']['mn_pid']) ? $menu_list['d']['mn_pid'] : 0);
            $return['d']['nav'] = explode(',', '后台管理,导航菜单,修改导航');  //设定导航
            $return['d']['nav_adm'] = array('adm' => 'list', 'name' =>'导航列表');  //设定导航
            $smarty -> assign('mn_type', array('其他', '左导航', '上导航'));   //设定导航
            $smarty -> assign('menu_html', $menu_html);
            $smarty -> assign('menu_list', $menu_list['d']);
            $return['d']['content'] = $smarty -> fetch($get_data['mod'].'/menu_nav_edit.html');
            $return['r'] = 1;
            $return['m'] = '';
        }
        break;
    }
    case 'del' : {  /*删除导航*/
        $id = get_var_value('id');
        $return = get_init('menu_nav') -> del_menu($id);
        break;
    }
    default : { /*列表显示*/
        $smarty -> assign('nav_types', explode(',', '其他,左导航,上导航'));   //导航类型
        $pg = get_var_value('pg');
        $pg_size = 15;
        if($pg < 1) $pg = 1;
        $menu_param = array(
            'WHERE' => ' AND mn_isdel=0',
            'ORDER' => ' mn_sort DESC',
            'LIMIT' => (($pg-1)*$pg_size).','.$pg_size,
            'COUNT' => TRUE,
        );
        $menu_nav = get_init('menu_nav') -> get_menu_nav($menu_param);
        $smarty -> assign('menu_nav', $menu_nav['d']);
        $smarty -> assign('page', get_page($menu_nav['n'], $pg, $pg_size));
        $return['d']['nav'] = explode(',', '后台管理,导航菜单,导航列表');  //设定导航
        $return['d']['nav_adm'] = array('adm' => 'add', 'name' =>'添加导航');  //设定导航
        $return['d']['content'] = $smarty -> fetch($get_data['mod'].'/menu_nav.html');
        $return['r'] = 1;
        $return['m'] = 'success';
    }
}
die(json_encode($return));
?>