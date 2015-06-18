<?php
/**
 * @copyright 2015
 * @description: cls_menu_nav
 * @file: cls_menu_nav.php
 * @charset: UTF-8
 * @create: 2015-04-13
 * @version 1.0
**/

class cls_menu_nav{
    private $mysqli  = NULL;        //数据库
    private $top_nav = array();     //头部导航
    private $table   = 'menu_nav';  //表名

    /**
     * @name: __construct
     * @description: 构造函数
     * @scope: public
     * @return: void
     * @create: 2015-04-13
    **/
    public function __construct(){
        $this -> mysqli = get_mod('mysqli', 'ADMIN');
    }

    /**
     * @name: get_menu_nav
     * @description: 查询导航
     * @scope: public
     * @param: array
     * @return: array
     * @create: 2015-04-13
    **/
    public function get_menu_nav($array=NULL){
        $return = array('r' => 0, 'd' => array(), 'n' => 0, 'm' => '');
        if(isset($array['FIELD']) && $array['FIELD'] != ''){
            $field = $array['FIELD'];
        }else{
            $field = get_init('global') -> set_mysql('ADMIN') -> get_table_fields($this -> mysqli -> get_table_name($this -> table), FALSE);
        }
        $where = $group = $order = $limit = '';
        if(isset($array['WHERE']) && $array['WHERE'] != '') $where = $array['WHERE'];
        if(isset($array['GROUP']) && $array['GROUP'] != '') $group = ' GROUP BY '.$array['GROUP'];
        if(isset($array['ORDER']) && $array['ORDER'] != '') $order = ' ORDER BY '.$array['ORDER'];
        if(isset($array['LIMIT']) && $array['LIMIT'] != '') $limit = ' LIMIT '.$array['LIMIT'];
        if(isset($array['COUNT']) && $array['COUNT']){
            $count = $this -> mysqli -> get_array_one('SELECT COUNT(*) AS num FROM '.$this -> mysqli -> get_table_name($this -> table).' WHERE 1 '.$where.$group);
            if(isset($count['num'])){
                $return['n'] = $count['num'];
                $return['r'] = 1;
            }
        }
        if(isset($array['ISONE']) && $array['ISONE']){
            $result = $this -> mysqli -> get_array_one('SELECT '.$field.' FROM '.$this -> mysqli -> get_table_name($this -> table).' WHERE 1 '.$where.$group.$order.$limit);
            !is_array($result) && $result = array();
        }else{
            $result = $this -> mysqli -> get_array('SELECT '.$field.' FROM '.$this -> mysqli -> get_table_name($this -> table).' WHERE 1 '.$where.$group.$order.$limit);
        }
        $return['d'] = $result;
        if(is_array($result) && count($result) > 0) $return['r'] = 1;
        return $return;
    }

    /**
     * @name: get_top
     * @description: 获取头部导航
     * @scope: private
     * @return: array
     * @create: 2015-04-14
    **/
    private function get_top(){
        $menu_top_nav = array();
        $list = $this -> get_menu_nav(array(
            'FIELD' => 'mn_id,mn_name,mn_url,mn_icon,mn_pid',
            'WHERE' => ' AND mn_type=2 AND mn_status=1 AND mn_isdel=0',
            'ORDER' => 'mn_sort ASC',
        ));
        if(is_foreach($list['d'])){
            $mn_pid = array();
            $is_super = TRUE;
            $ad_id = get_mod('sess') -> get('ad_id');
            $admin = get_init('admin') -> get_admin_byid($ad_id, 1);
            if(!isset($admin['adg_id']) || $admin['adg_id'] != SUPER_ADMIN_ID){
                $purview = get_init('admin') -> get_admin_purview();
                $is_super = FALSE;
            }
            foreach($list['d'] as $key => $val){
                if(!in_array($val['mn_id'], $mn_pid)){
                    if($is_super){
                        $mn_pid[] = $val['mn_id'];
                    }else{
                        if(is_foreach($purview) && in_array($val['mn_url'], $purview)) $mn_pid[] = $val['mn_id'];
                    }
                }
            }
            $this -> top_nav = $mn_pid;
            $menu_top_nav = $this -> get_by_index(0, $list['d']);
        }
        return $menu_top_nav;
    }

    /**
     * @name: get_left
     * @description: 获取左导航
     * @scope: private
     * @return: array
     * @create: 2015-04-14
    **/
    private function get_left(){
        $menu_left_nav = array();
        $list = $this -> get_menu_nav(array(
            'FIELD' => 'mn_id,mn_name,mn_url,mn_icon,mn_pid',
            'WHERE' => ' AND mn_type=1 AND mn_isdel=0 AND mn_status=1',
            'ORDER' => 'mn_sort ASC',
        ));
        if(is_foreach($list['d'])){
            $mn_pid = $this -> top_nav;
            $ad_id = get_mod('sess') -> get('ad_id');
            $admin = get_init('admin') -> get_admin_byid($ad_id, 1);
            if(!isset($admin['adg_id']) || $admin['adg_id'] != SUPER_ADMIN_ID){
                $purview = get_init('admin') -> get_admin_purview();
                $is_super = FALSE;
                $left_list = array();
                foreach($list['d'] as $val){
                    if(is_foreach($purview) && in_array($val['mn_url'], $purview)) $left_list[] = $val;
                }
                if(is_foreach($mn_pid)) foreach($mn_pid as $index){
                    $tmp = $this -> get_by_index($index, $left_list);
                    if(is_foreach($tmp)) $menu_left_nav[$index] = $tmp;
                }
            }else{
                if(is_foreach($mn_pid)) foreach($mn_pid as $index){
                    $tmp = $this -> get_by_index($index, $list['d']);
                    if(is_foreach($tmp)) $menu_left_nav[$index] = $tmp;
                }
            }
        }
        return $menu_left_nav;
    }

    /**
     * @name: get_by_index
     * @description: 生成数组层级关系
     * @scope: private
     * @return: array
     * @create: 2015-04-14
    **/
    private function get_by_index($index, $list){
        $return = array();
        if(is_foreach($list)) foreach($list as $val){
            if($val['mn_pid'] == $index){
                $tmp = $this -> get_by_index($val['mn_id'], $list);
                if(is_foreach($tmp)) $val['list'] = $tmp;
                unset($val['mn_pid']);
                $return[] = $val;
            }
        }
        return $return;
    }

    /**
     * @name: get_menu
     * @description: 获取导航菜单
     * @scope: public
     * @return: array
     * @create: 2015-04-14
    **/
    public function get_menu(){
        return array('top' => $this -> get_top(), 'left' => $this -> get_left());
    }

    /**
     * @name: del_menu
     * @description: 删除导航菜单
     * @scope: public
     * @param: integer 菜单ID
     * @return: array
     * @create: 2015-04-18
    **/
    public function del_menu($id){
        $return = array('r' => 0, 'm' => '错误的ID');
        if($id < 1) return $return;
        $result = $this -> mysqli -> get_query('UPDATE '.$this -> mysqli -> get_table_name($this -> table).' SET mn_isdel=1 WHERE mn_id=\''.$id.'\'');
        $menu = $this -> get_menu_byid($id);
        if($result){
            $return = array('r' => 1, 'm' => '删除导航菜单[mn_id='.$id.']'.(isset($menu['mn_name']) ? '['.$menu['mn_name'].']' : '').'成功');
            $log_result = 1;
        }else{
            $return['m'] = '删除导航菜单[mn_id='.$id.']'.(isset($menu['mn_name']) ? '['.$menu['mn_name'].']' : '').'失败';
            $log_result = 0;
        }
        get_init('admin_log') -> log_add($return['m'], $log_result);
        return $return;
    }

    /**
     * @name: get_menu_relation
     * @description: 查询有效菜单关联列表
     * @scope: public
     * @return: array
     * @author: Zhang Xue Feng
     * @create: 2015-04-19
    **/
    public function get_menu_relation(){
        $return = array();
        $list = $this -> get_menu_nav(array(
            'FIELD' => 'mn_id,mn_name,mn_url,mn_icon,mn_pid',
            'WHERE' => ' AND mn_isdel=0 AND mn_status=1',
            'ORDER' => 'mn_sort ASC',
        ));
        if(is_foreach($list['d'])){
            $return = $this -> get_by_index(0, $list['d']);
        }
        return $return;
    }

    /**
     * @name: menu_arraytohtml
     * @description: 菜单列表生成OPTION
     * @scope: public
     * @param: array 菜单列表
     * @param: integer 当前深度
     * @param: integer 当前选择
     * @return: string
     * @author: Zhang Xue Feng
     * @create: 2015-04-19
    **/
    public function menu_arraytohtml($menu_list, $depth=0, $id=0){
        $html = '';
        foreach($menu_list as $val){
            $html .= '<option value="'.$val['mn_id'].'"'.($id == $val['mn_id'] ? ' selected' : '').'>'.($depth > 0 ? str_repeat('---', $depth) : '').$val['mn_name'].'</option>';
            if(isset($val['list']) && is_foreach($val['list'])){
                $html .= $this -> menu_arraytohtml($val['list'], $depth+1, $id);
            }
        }
        return $html;
    }

    /**
     * @name: menu_nav_add
     * @description: 添加菜单
     * @scope: public
     * @param: array 菜单列表数据
     * @return: boolean
     * @author: Zhang Xue Feng
     * @create: 2015-04-19
    **/
    public function menu_nav_add($data){
        $return = FALSE;
        $sql = get_init('global') -> set_mysql('ADMIN') -> get_sql_insert($data, $this -> table);
        $log_result = 0;
        $content = '添加菜单'.(isset($data['mn_name']) ? '[mn_name='.$data['mn_name'].']' : '');
        if($sql != ''){
            $return = $this -> mysqli -> get_query($sql);
            if($return) $log_result = 1;
        }
        get_init('admin_log') -> log_add($content, $log_result);
        return $return;
    }

    /**
     * @name: menu_nav_edit
     * @description: 修改菜单
     * @scope: public
     * @param: array 菜单列表数据
     * @param: integer 菜单ID
     * @return: boolean
     * @author: Zhang Xue Feng
     * @create: 2015-04-20
    **/
    public function menu_nav_edit($data, $id){
        $return = FALSE;
        if($id < 1) return $return;
        $sql = get_init('global') -> set_mysql('ADMIN') -> get_sql_update($data, $this -> table, 'mn_id=\''.$id.'\'');
        $log_result = 0;
        $menu = $this -> get_menu_byid($id);
        $content = '修改菜单[mn_id='.$id.']'.(isset($menu['mn_name']) ? '[mn_name='.$menu['mn_name'].']' : '');
        if($sql != ''){
            $return = $this -> mysqli -> get_query($sql);
            if($return) $log_result = 1;
        }
        get_init('admin_log') -> log_add($content, $log_result);
        return $return;
    }

    /**
     * @name: get_menu_byid
     * @description: 查询菜单列表信息
     * @param: integer 菜单ID
     * @return: array
     * @author: Zhang Xue Feng
     * @create: 2015-04-21
    **/
    public function get_menu_byid($id){
        $result = $this -> get_menu_nav(array(
            'WHERE' => ' AND mn_id=\''.$id.'\'',
            'ISONE' => TRUE,
        ));
        return $result['d'];
    }
}
?>