<?php
/**
 * @copyright 2015
 * @description: cls_admin_log
 * @file: cls_admin_log.php
 * @charset: UTF-8
 * @create: 2015-04-20
 * @version 1.0
**/

class cls_admin_log{
    private $mysqli = NULL;
    private $table  = 'admin_log';

    /**
     * @name: __construct
     * @description: 构造函数
     * @scope: public
     * @return: void
     * @create: 2015-04-20
    **/
    public function __construct(){
        $this -> mysqli = get_mod('mysqli', 'ADMIN');
    }

    /**
     * @name: get_admin_log
     * @description: 获取管理员日志
     * @scope: public
     * @param: array default[NULL]
     * @return: array
     * @create: 2015-04-20
    **/
    public function get_admin_log($array=NULL){
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
     * @name: log_del
     * @description: 删除管理员日志
     * @scope: public
     * @param: integer 日志ID
     * @return: array
     * @create: 2015-04-20
    **/
    public function log_del($id){
        $return = array('r' => 0, 'm' => '错误的ID');
        if($id < 1) return $return;
        $result = $this -> mysqli -> get_query('UPDATE '.$this -> mysqli -> get_table_name($this -> table).' SET ad_isdel=1 WHERE ad_id=\''.$id.'\'');
        if($result){
            $return = array('r' => 1, 'm' => '删除成功');
        }else{
            $return['m'] = '删除失败';
        }
        return $return;
    }

    /**
     * @name: log_add
     * @description: 添加管理员日志
     * @scope: public
     * @param: string 日志内容
     * @param: integer 操作结果
     * @return: boolean
     * @create: 2015-04-20
    **/
    public function log_add($content, $result=1){
        if($content == '') return FALSE;
        $sql = 'INSERT INTO '.$this -> mysqli -> get_table_name($this -> table).' (`al_id`, `ad_id`, `al_result`, `al_content`, `al_isdel`, `al_lasttime`, `al_lastip`) VALUES (NULL, \''.get_init('admin') -> get_ad_id().'\', \''.$result.'\', \''.$content.'\', 0, \''.SYSTEM_THIS_TIME.'\', \''.SYSTEM_THIS_IP.'\')';
        return $this -> mysqli -> get_query($sql);
    }
}
?>