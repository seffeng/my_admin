<?php
/**
 * @copyright 2015
 * @description: cls_purview_group
 * @file: cls_purview_group.php
 * @charset: UTF-8
 * @create: 2015-04-24
 * @version 1.0
**/

class cls_purview_group{
    private $mysqli = NULL;
    private $table  = 'purview_group';

    /**
     * @name: __construct
     * @description: 构造函数
     * @scope: public
     * @return: void
     * @create: 2015-04-24
    **/
    public function __construct(){
        $this -> mysqli = get_mod('mysqli', 'ADMIN');
    }

    /**
     * @name: get_purview_group
     * @description: 获取权限组信息
     * @scope: public
     * @param: array default[NULL]
     * @return: array
     * @create: 2015-04-24
    **/
    public function get_purview_group($array=NULL){
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
     * @name: purview_group_del
     * @description: 删除权限组
     * @scope: public
     * @param: integer 权限组ID
     * @return: array
     * @create: 2015-04-24
    **/
    public function purview_group_del($id){
        $return = array('r' => 0, 'm' => '错误的ID');
        if($id < 1) return $return;
        $result = $this -> mysqli -> get_query('UPDATE '.$this -> mysqli -> get_table_name($this -> table).' SET pvg_isdel=1 WHERE pvg_id=\''.$id.'\'');
        $purview_group = $this -> get_purview_group_byid($id);
        if($result){
            $return = array('r' => 1, 'm' => '删除权限组成功[pvg_id='.$id.']['.(isset($purview_group['pvg_name']) ? $purview_group['pvg_name'] : '').']');
            $log_result = 1;
        }else{
            $return['m'] = '删除权限组失败[pvg_id='.$id.']['.(isset($purview_group['pvg_name']) ? $purview_group['pvg_name'] : '').']';
            $log_result = 0;
        }
        get_init('admin_log') -> log_add($return['m'], $log_result);
        return $return;
    }

    /**
     * @name: purview_group_add
     * @description: 添加权限组
     * @scope: public
     * @param: array 权限组数据
     * @return: array
     * @create: 2015-04-24
    **/
    public function purview_group_add($data){
        $return = FALSE;
        $sql = get_init('global') -> set_mysql('ADMIN') -> get_sql_insert($data, $this -> table);
        if($sql == '') return $return;
        $return = $this -> mysqli -> get_query($sql);
        if($return){
            $log_result = 1;
            $return = $this -> mysqli -> get_insert_id();
        }else{
            $log_result = 0;
        }
        $log_content = '添加权限组['.(isset($data['pvg_name']) ? $data['pvg_name'] : '').']';
        get_init('admin_log') -> log_add($log_content, $log_result);
        return $return;
    }

    /**
     * @name: purview_group_edit
     * @description: 修改权限组
     * @param: array 权限组数据
     * @param: integer 权限组ID
     * @return: boolean
     * @author: Zhang Xue Feng
     * @create: 2015-04-24
    **/
    public function purview_group_edit($data, $id){
        $return = FALSE;
        if($id < 1) return $return;
        $sql = get_init('global') -> set_mysql('ADMIN') -> get_sql_update($data, $this -> table, 'pvg_id=\''.$id.'\'');
        if($sql == '') return $return;
        $return = $this -> mysqli -> get_query($sql);
        $log_result = $return ? 1 : 0;
        $purview_group = $this -> get_purview_group_byid($id);
        $log_content = '修改权限组信息[pvg_id='.$id.']'.(isset($purview_group['pvg_name']) ? '['.$purview_group['pvg_name'].']' : '');
        get_init('admin_log') -> log_add($log_content, $log_result);
        return $return;
    }

    /**
     * @name: get_purview_group_byid
     * @description: 查询权限组信息
     * @param: integer 权限组ID
     * @return: array
     * @author: Zhang Xue Feng
     * @create: 2015-04-24
    **/
    public function get_purview_group_byid($id){
        if($id < 1) return array();
        $purview_group = $this -> get_purview_group(array(
            'WHERE' => ' AND pvg_id=\''.$id.'\'',
            'ISONE' => TRUE,
        ));
        return $purview_group['d'];
    }
}
?>