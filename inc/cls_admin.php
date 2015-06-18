<?php
/**
 * @copyright 2015
 * @description: cls_admin
 * @file: cls_admin.php
 * @charset: UTF-8
 * @version 1.0
**/

class cls_admin{
    public $table_admin = 'admin';       /*管理员表*/
    public $table_info  = 'admin_info';  /*管理员信息表*/
    private static $pv_ids = array();
    private static $purviews = array();

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
     * @name: set_table
     * @description: set_table
     * @scope: public
     * @param: string value
     * @return: void
     * @create: 2015-5-28
    **/
    public function set_table($table){
        $this -> table = $table;
        return;
    }

    /**
     * @name: set_table_info
     * @description: set_table_info
     * @scope: public
     * @param: string value
     * @return: void
     * @create: 2015-5-28
    **/
    public function set_table_info($table_info){
        $this -> table_info = $table_info;
        return;
    }

    /**
     * @name: is_login
     * @description: 判断是否登录
     * @scope: public
     * @return: boolean
    **/
    public function is_login(){
        if($this -> get_ad_id() > 0) return TRUE;
        return FALSE;
    }

    /**
     * @name: get_ad_id
     * @description: 获取管理员ID
     * @scope: public
     * @return: integer
    **/
    public function get_ad_id(){
        return intval(get_mod('sess') -> get('ad_id'));
    }

    /**
     * @name: get_admin
     * @description: 获取管理员信息
     * @scope: public
     * @param: array default[NULL]
     * @return: array
    **/
    public function get_admin($array=NULL){
        $return = array('r' => 0, 'd' => array(), 'n' => 0, 'm' => '');
        if(isset($array['FIELD']) && $array['FIELD'] != ''){
            $field = $array['FIELD'];
        }else{
            $field = get_init('global') -> set_mysql('ADMIN') -> get_table_fields($this -> mysqli -> get_table_name($this -> table_admin), FALSE);
        }
        $where = $group = $order = $limit = '';
        if(isset($array['WHERE']) && $array['WHERE'] != '') $where = $array['WHERE'];
        if(isset($array['GROUP']) && $array['GROUP'] != '') $group = ' GROUP BY '.$array['GROUP'];
        if(isset($array['ORDER']) && $array['ORDER'] != '') $order = ' ORDER BY '.$array['ORDER'];
        if(isset($array['LIMIT']) && $array['LIMIT'] != '') $limit = ' LIMIT '.$array['LIMIT'];
        if(isset($array['COUNT']) && $array['COUNT']){
            $count = $this -> mysqli -> get_array_one('SELECT COUNT(*) AS num FROM '.$this -> mysqli -> get_table_name($this -> table_admin).' WHERE 1 '.$where.$group);
            if(isset($count['num'])){
                $return['n'] = $count['num'];
                $return['r'] = 1;
            }
        }
        if(isset($array['ISONE']) && $array['ISONE']){
            $result = $this -> mysqli -> get_array_one('SELECT '.$field.' FROM '.$this -> mysqli -> get_table_name($this -> table_admin).' WHERE 1 '.$where.$group.$order.$limit);
            !is_array($result) && $result = array();
        }else{
            $result = $this -> mysqli -> get_array('SELECT '.$field.' FROM '.$this -> mysqli -> get_table_name($this -> table_admin).' WHERE 1 '.$where.$group.$order.$limit);
        }
        $return['d'] = $result;
        if(is_array($result) && count($result) > 0) $return['r'] = 1;
        return $return;
    }

    /**
     * @name: login
     * @description: 管理员登录
     * @scope: public
     * @param: string   账号
     * @param: string   密码
     * @return: integer
    **/
    public function login($username, $userpass){
        $return = array('r' => 0, 'm' => '账号或密码错误');
        if($username == '' || $userpass == '') return $return;
        $result = $this -> get_admin(array(
            'FIELD' => 'ad_id,ad_username,ad_status',
            'WHERE' => ' AND ad_username=\''.$username.'\' AND ad_password=\''.$this -> en_password($userpass).'\' AND ad_isdel=0',
            'ISONE' => TRUE,
        ));
        if($result['r'] == 1 && isset($result['d']['ad_status'])){
            if($result['d']['ad_status'] == '2'){
                $return['m'] = '账户已停用';
            }else{
                $return['r'] = 1;
                $return['m'] = '';
                $admin_info = $this -> get_admin_info(array(
                    'WHERE' => ' AND ad_id=\''.$result['d']['ad_id'].'\'',
                    'ISONE' => TRUE,
                    'FIELD' => 'ai_name',
                ));
                $ai_name = isset($admin_info['d']['ai_name']) ? $admin_info['d']['ai_name'] : $result['d']['ad_username'];
                get_mod('sess') -> set('ai_name', $ai_name);
                get_mod('sess') -> set('ad_id', $result['d']['ad_id']);
                get_mod('sess') -> set('ad_username', $result['d']['ad_username']);
            }
        }
        return $return;
    }

    /**
     * @name: en_password
     * @description: 加密密码
     * @scope: public
     * @param: string  密码
     * @return: string
    **/
    public function en_password($userpass){
        $md5_pass = md5($userpass);
        return md5(substr($md5_pass, hexdec($md5_pass[0]), hexdec($md5_pass[1])).$userpass);
    }

    /**
     * @name: logout
     * @description: 注销登录
     * @scope: public
     * @return: boolean
    **/
    public function logout(){
        get_mod('sess') -> del('ad_id');
        get_mod('sess') -> del('ad_username');
        return TRUE;
    }

    /**
     * @name: get_admin_info
     * @description: 获取管理员详细信息
     * @scope: public
     * @param: array default[NULL]
     * @return: array
    **/
    public function get_admin_info($array=NULL){
        $return = array('r' => 0, 'd' => array(), 'n' => 0, 'm' => '');
        if(isset($array['FIELD']) && $array['FIELD'] != ''){
            $field = $array['FIELD'];
        }else{
            $field = get_init('global') -> set_mysql('ADMIN') -> get_table_fields($this -> mysqli -> get_table_name($this -> table_info), FALSE);
        }
        $where = $group = $order = $limit = '';
        if(isset($array['WHERE']) && $array['WHERE'] != '') $where = $array['WHERE'];
        if(isset($array['GROUP']) && $array['GROUP'] != '') $group = ' GROUP BY '.$array['GROUP'];
        if(isset($array['ORDER']) && $array['ORDER'] != '') $order = ' ORDER BY '.$array['ORDER'];
        if(isset($array['LIMIT']) && $array['LIMIT'] != '') $limit = ' LIMIT '.$array['LIMIT'];
        if(isset($array['COUNT']) && $array['COUNT']){
            $count = $this -> mysqli -> get_array_one('SELECT COUNT(*) AS num FROM '.$this -> mysqli -> get_table_name($this -> table_info).' WHERE 1 '.$where.$group);
            if(isset($count['num'])){
                $return['n'] = $count['num'];
                $return['r'] = 1;
            }
        }
        if(isset($array['ISONE']) && $array['ISONE']){
            $result = $this -> mysqli -> get_array_one('SELECT '.$field.' FROM '.$this -> mysqli -> get_table_name($this -> table_info).' WHERE 1 '.$where.$group.$order.$limit);
            !is_array($result) && $result = array();
        }else{
            $result = $this -> mysqli -> get_array('SELECT '.$field.' FROM '.$this -> mysqli -> get_table_name($this -> table_info).' WHERE 1 '.$where.$group.$order.$limit);
        }
        $return['d'] = $result;
        if(is_array($result) && count($result) > 0) $return['r'] = 1;
        return $return;
    }

    /**
     * @name: del_admin
     * @description: 删除管理员
     * @scope: public
     * @param: integer 管理员ID
     * @return: array
    **/
    public function del_admin($id){
        $return = array('r' => 0, 'm' => '错误的ID');
        if($id < 1) return $return;
        $result = $this -> mysqli -> get_query('UPDATE '.$this -> mysqli -> get_table_name($this -> table_admin).' SET ad_isdel=1 WHERE ad_id=\''.$id.'\'');
        $admin = $this -> get_admin_byid($id);
        if($result){
            $return = array('r' => 1, 'm' => '删除管理员成功[ad_id='.$id.']['.(isset($admin['ad_username']) ? $admin['ad_username'] : '').']');
            $log_result = 1;
        }else{
            $return['m'] = '删除管理员失败[ad_id='.$id.']['.(isset($admin['ad_username']) ? $admin['ad_username'] : '').']';
            $log_result = 0;
        }
        get_init('admin_log') -> log_add($return['m'], $log_result);
        return $return;
    }

    /**
     * @name: admin_add
     * @description: 添加管理员
     * @scope: public
     * @param: array 管理员数据
     * @return: array
    **/
    public function admin_add($data){
        $return = FALSE;
        $sql = get_init('global') -> set_mysql('ADMIN') -> get_sql_insert($data, 'admin');
        if($sql == '') return $return;
        $return = $this -> mysqli -> get_query($sql);
        if($return){
            $log_result = 1;
            $return = $this -> mysqli -> get_insert_id();
        }else{
            $log_result = 0;
        }
        $log_content = '添加管理员['.(isset($data['ad_username']) ? $data['ad_username'] : '').']';
        get_init('admin_log') -> log_add($log_content, $log_result);
        return $return;
    }

    /**
     * @name: admin_edit
     * @description: 修改管理员
     * @param: array 管理员数据
     * @param: integer 管理员ID
     * @return: boolean
    **/
    public function admin_edit($data, $id){
        $return = FALSE;
        if($id < 1) return $return;
        $sql = get_init('global') -> set_mysql('ADMIN') -> get_sql_update($data, 'admin', 'ad_id=\''.$id.'\'');
        if($sql == '') return $return;
        $return = $this -> mysqli -> get_query($sql);
        $log_result = $return ? 1 : 0;
        $admin = $this -> get_admin_byid($id, 1);
        $log_content = '修改管理员信息[ad_id='.$id.']'.(isset($admin['ad_username']) ? '['.$admin['ad_username'].']' : '');
        get_init('admin_log') -> log_add($log_content, $log_result);
        return $return;
    }

    /**
     * @name: get_admin_byid
     * @description: 查询管理员信息
     * @param: integer 管理员ID
     * @param: integer 级别default[0-详细][1-admin][2-admin_info]
     * @return: array
    **/
    public function get_admin_byid($id, $level=0){
        $admin = $admin_info = array('d' => array());
        if($id < 1) return array();
        if($level == 0){
            $admin = $this -> get_admin(array(
                'WHERE' => ' AND ad_id=\''.$id.'\' AND ad_isdel=0',
                'ISONE' => TRUE,
            ));
            $admin_info = $this -> get_admin_info(array(
                'WHERE' => ' AND ad_id=\''.$id.'\'',
                'ISONE' => TRUE,
            ));
        }elseif($level == 1){
            $admin = $this -> get_admin(array(
                'WHERE' => ' AND ad_id=\''.$id.'\' AND ad_isdel=0',
                'ISONE' => TRUE,
            ));
        }elseif($level == 2){
            $admin_info = $this -> get_admin_info(array(
                'WHERE' => ' AND ad_id=\''.$id.'\'',
                'ISONE' => TRUE,
            ));
        }
        return array_merge($admin['d'], $admin_info['d']);
    }

    /**
     * @name: admin_info_add
     * @description: 添加管理员资料
     * @scope: public
     * @param: array 管理员数据
     * @return: array
    **/
    public function admin_info_add($data){
        $return = FALSE;
        $sql = get_init('global') -> set_mysql('ADMIN') -> get_sql_insert($data, $this -> table_info);
        if($sql == '') return $return;
        return $this -> mysqli -> get_query($sql);
    }

    /**
     * @name: admin_info_edit
     * @description: 修改管理员
     * @param: array 管理员数据
     * @param: integer 管理员ID
     * @return: boolean
     * @author: Zhang Xue Feng
    **/
    public function admin_info_edit($data, $id){
        $return = FALSE;
        if($id < 1) return $return;
        $admin_info = $this -> get_admin_info(array('WHERE' => ' AND ad_id=\''.$id.'\'', 'ISONE' => TRUE));
        if(is_foreach($admin_info['d'])){
            $sql = get_init('global') -> set_mysql('ADMIN') -> get_sql_update($data, $this -> table_info, 'ad_id=\''.$id.'\'');
            if($sql == '') return $return;
            return $this -> mysqli -> get_query($sql);
        }else{
            $data['ad_id'] = $id;
            return $this -> admin_info_add($data);
        }
    }

    /**
     * @name: check_purview
     * @description: 检查权限
     * @param: string 权限
     * @return: boolean
     * @author: Zhang Xue Feng
     * @create: 2015-04-24
    **/
    public function check_purview($key){
        if($key == '') return FALSE;
        $ad_id = get_mod('sess') -> get('ad_id');
        if($key == 'sys-admin-edit' && get_var_value('id') == $ad_id) return TRUE;
        $admin = $this -> get_admin_byid($ad_id, 1);
        if(isset($admin['adg_id']) && $admin['adg_id'] == SUPER_ADMIN_ID) return TRUE;
        $purview = $this -> get_admin_purview();
        if(in_array($key, $purview)) return TRUE;
        return FALSE;
    }

    /**
     * @name: get_admin_purview
     * @description: 获取用户权限
     * @scope: public
     * @return: array
     * @create: 2015-04-24
    **/
    public function get_admin_purview(){
        if(count(self::$purviews) > 0) return self::$purviews;
        $pv_ids = $this -> get_admin_pv_ids();
        if(is_foreach($pv_ids)){
            $purview = get_init('purview') -> get_purview(array(
                'FIELD' => 'pv_id,pv_key',
                'WHERE' => ' AND pv_id IN ('.implode(',', $pv_ids).') AND pv_status=1 AND pv_isdel=0',
            ));
            if(is_foreach($purview['d'])) foreach($purview['d'] as $val){
                self::$purviews[$val['pv_id']] = $val['pv_key'];
            }
        }
        return self::$purviews;
    }

    /**
     * @name: get_admin_pv_ids
     * @description: 获取用户权限ID
     * @scope: public
     * @return: array
     * @create: 2015-04-24
    **/
    public function get_admin_pv_ids(){
        $ad_id = get_mod('sess') -> get('ad_id');
        if($ad_id < 0) return FALSE;
        if(count(self::$pv_ids) > 0) return self::$pv_ids;
        $tmp_pv_ids = $tmp_pvg_ids = '';
        $admin = $this -> get_admin_byid($ad_id, 1);
        if(is_foreach($admin)){
            $tmp_pv_ids .= $admin['pv_ids'];
            $tmp_pvg_ids .= $admin['pvg_ids'];
            $admin_group = get_init('admin_group') -> get_admin_group(array(
                'FIELD' => 'pv_ids,pvg_ids',
                'WHERE' => ' AND adg_id=\''.$admin['adg_id'].'\' AND adg_status=1 AND adg_isdel=0',
            ));
            if(is_foreach($admin_group['d'])) foreach($admin_group['d'] as $val){
                $tmp_pv_ids .= $val['pv_ids'];
                $tmp_pvg_ids .= $val['pvg_ids'];
            }
        }
        if(isset($admin['pvg_ids']) && isset($admin['pvg_ids']['2'])){
            $tmp_pv_ids = preg_replace_callback('/[,]{2,}/', function($match){return ',';}, $tmp_pv_ids.$admin['pv_ids']);
            $tmp_pvg_ids = preg_replace_callback('/[,]{2,}/', function($match){return ',';}, $tmp_pvg_ids.$admin['pvg_ids']);
        }
        if(isset($tmp_pvg_ids['1'])){
            $purview_group = get_init('purview_group') -> get_purview_group(array(
                'FIELD' => 'pv_ids',
                'WHERE' => ' AND pvg_id IN ('.trim($tmp_pvg_ids, ',').') AND pvg_status=1 AND pvg_isdel=0',
            ));
            if(is_foreach($purview_group['d'])) foreach($purview_group['d'] as $val){
                $tmp_pv_ids .= $val['pv_ids'];
            }
        }
        self::$pv_ids = array_filter(array_unique(explode(',', $tmp_pv_ids)));
        return self::$pv_ids;
    }
}
?>