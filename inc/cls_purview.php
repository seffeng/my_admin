<?php
/**
 * @copyright 2015
 * @description: cls_purview
 * @file: cls_purview.php
 * @charset: UTF-8
 * @create: 2015-04-24
 * @version 1.0
**/

class cls_purview{
    private $mysqli = NULL;
    private $table  = 'purview';

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
     * @name: get_purview
     * @description: 获取权限信息
     * @scope: public
     * @param: array default[NULL]
     * @return: array
     * @create: 2015-04-24
    **/
    public function get_purview($array=NULL){
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
     * @name: purview_del
     * @description: 删除权限
     * @scope: public
     * @param: integer 权限ID
     * @return: array
     * @create: 2015-04-24
    **/
    public function purview_del($id){
        $return = array('r' => 0, 'm' => '错误的ID');
        if($id < 1) return $return;
        $result = $this -> mysqli -> get_query('UPDATE '.$this -> mysqli -> get_table_name($this -> table).' SET pv_isdel=1 WHERE pv_id=\''.$id.'\'');
        $purview = $this -> get_purview_byid($id);
        if($result){
            $return = array('r' => 1, 'm' => '删除权限成功[pv_id='.$id.']['.(isset($purview['pv_name']) ? $purview['pv_name'] : '').']');
            $log_result = 1;
        }else{
            $return['m'] = '删除管理员失败[pv_id='.$id.']['.(isset($purview['pv_name']) ? $purview['pv_name'] : '').']';
            $log_result = 0;
        }
        get_init('admin_log') -> log_add($return['m'], $log_result);
        return $return;
    }

    /**
     * @name: purview_add
     * @description: 添加权限
     * @scope: public
     * @param: array 权限数据
     * @return: array
     * @create: 2015-04-24
    **/
    public function purview_add($data){
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
        $log_content = '添加权限['.(isset($data['pv_name']) ? $data['pv_name'] : '').']';
        get_init('admin_log') -> log_add($log_content, $log_result);
        return $return;
    }

    /**
     * @name: purview_edit
     * @description: 修改管理员
     * @param: array 管理员数据
     * @param: integer 管理员ID
     * @return: boolean
     * @author: Zhang Xue Feng
     * @create: 2015-04-24
    **/
    public function purview_edit($data, $id){
        $return = FALSE;
        if($id < 1) return $return;
        $sql = get_init('global') -> set_mysql('ADMIN') -> get_sql_update($data, $this -> table, 'pv_id=\''.$id.'\'');
        if($sql == '') return $return;
        $return = $this -> mysqli -> get_query($sql);
        $log_result = $return ? 1 : 0;
        $purview = $this -> get_purview_byid($id);
        $log_content = '修改管理员信息[pv_id='.$id.']'.(isset($purview['pv_name']) ? '['.$purview['pv_name'].']' : '');
        get_init('admin_log') -> log_add($log_content, $log_result);
        return $return;
    }

    /**
     * @name: get_purview_byid
     * @description: 查询权限信息
     * @param: integer 权限ID
     * @return: array
     * @author: Zhang Xue Feng
     * @create: 2015-04-24
    **/
    public function get_purview_byid($id){
        if($id < 1) return array();
        $purview = $this -> get_purview(array(
            'WHERE' => ' AND pv_id=\''.$id.'\'',
            'ISONE' => TRUE,
        ));
        return $purview['d'];
    }
}
?>