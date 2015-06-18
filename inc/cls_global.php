<?php
/**
 * @copyright 2015
 * @description: cls_global
 * @file: cls_global.php
 * @charset: UTF-8
 * @version 1.0
**/

class cls_global{
    private $cur_a  = NULL;      /* 请求操作 */
    private $cur_o  = NULL;      /* 请求操作[save] */
    private $cur_s  = NULL;      /* sessioin_id */
    private $cfg    = NULL;      /* 配置 */
    private $mod    = NULL;      /* 本次模块 */
    private $mods   = NULL;      /* 本次模块下子模块 */
    private $action = NULL;      /* 本次动作 */
    private $mysql  = NULL;      /* mod_mysql */

    /**
     * @name: __construct
     * @description: 构造函数
     * @scope: public
     * @return: void
    **/
    public function __construct(){
        $this -> load_cfg();
    }

    /**
     * @name: load_cfg
     * @description: 加载配置
     * @scope: private
     * @return: void
    **/
    private function load_cfg(){
        $this -> cfg = isset($GLOBALS['CFG']) ? $GLOBALS['CFG'] : NULL;
        $this -> cfg_data();        //配置基础数据
        $this -> cfg_log();         //日志配置
        $this -> cfg_smarty();      //SMARTY配置
        $this -> cfg_session();     //SESSION配置
        $this -> cfg_cache();       //CACHE配置
        $this -> cfg_mysql();       //MYSQL配置
    }

    /**
     * @name: cfg_get
     * @description: 读取当前配置
     * @scope: public
     * @param: string 配置名称[多级数组,分割]
     * @return: mixed
    **/
    public function cfg_get($key){
        $key = trim($key);
        if($key == '') return NULL;
        if(strpos($key, ',') !== FALSE){
            $tmp = NULL;
            foreach(explode(',', $key) as $val){
                if($tmp == NULL){
                    if(isset($this -> cfg[$val])){
                        $tmp = $this -> cfg[$val];
                    }else{
                        return NULL;
                    }
                }else{
                    if(isset($tmp[$val])){
                        $tmp = $tmp[$val];
                    }else{
                        return NULL;
                    }
                }
            }
            return $tmp;
        }else{        
            return isset($this -> cfg[$key]) ? $this -> cfg[$key] : NULL;
        }
    }

    /**
     * @name: cfg_data
     * @description: 配置数据DATA
     * @scope: private
     * @return: void
    **/
    private function cfg_data(){
        $this -> cur_a = strtolower(preg_replace_callback("/[^a-z\d_-]/i", function($match){ return ''; }, get_var_value('a')));
        $this -> cur_o = preg_replace_callback("/[^a-z\d_]/i", function($match){return '';}, get_var_value('o'));   /* 请求操作 */
        $this -> cur_s = substr(preg_replace_callback("/[^a-z\d]/i", function($match){return '';}, get_var_value('s')), 0, 32); /* SESSION_ID */
        if($this -> cur_a == '') $this -> cur_a = 'sys-index';  /* 默认首页 */
        if(strpos($this -> cur_a, '-') === FALSE){
            switch($this -> cur_a){
                case 'login' : { $this -> cur_a = 'sys-login'; break; } /* 登录 */
                case 'logout' : { $this -> cur_a = 'sys-logout'; break; } /* 登出 */
                default:{   /* 只有模块 */
                    $this -> cur_a = $this -> cur_a.'-index';
                }
            }
        }
        $tmp = explode('-', $this -> cur_a);
        $this -> mod = $this -> mods = $this -> action = '';    /* 置空 */
        if(isset($tmp[0])) $this -> mod = $tmp[0];
        if(isset($tmp[1])) $this -> mods = $tmp[1];
        if(isset($tmp[2])) $this -> action = $tmp[2];
    }

    /**
     * @name: get_data
     * @description: 获取本次数据
     * @scope: public
     * @return: array
    **/
    public function get_data(){
        return array('a' => $this -> cur_a, 'o' => $this -> cur_o, 's' => $this -> cur_s, 'action' => $this -> action, 'mod' => $this -> mod, 'mods' => $this -> mods);
    }

    /**
     * @name: cfg_log
     * @description: 配置LOG日志
     * @scope: private
     * @return: void
    **/
    private function cfg_log(){
        get_mod('log') -> set_conf(array('LOG_DATAURL' => $this -> cfg_get('LOG_DATAURL')));
    }

    /**
     * @name: cfg_smarty
     * @description: 配置SMARTY
     * @scope: private
     * @return: void
    **/
    private function cfg_smarty(){
        get_mod('smarty') -> set_conf(array(
            'SMARTY_COMPILE_CHECK'   => $this -> cfg_get('SMARTY_COMPILE_CHECK'),
            'SMARTY_DEBUG'           => $this -> cfg_get('SMARTY_DEBUG'),
            'SMARTY_CACHE'           => $this -> cfg_get('SMARTY_CACHE'),
            'SMARTY_USE_SUB_DIRS'    => $this -> cfg_get('SMARTY_USE_SUB_DIRS'),
            'SMARTY_CACHE_LIFE_TIME' => $this -> cfg_get('SMARTY_CACHE_LIFE_TIME'),
            'SMARTY_LEFT_DELI'       => $this -> cfg_get('SMARTY_LEFT_DELI'),
            'SMARTY_RIGHT_DELI'      => $this -> cfg_get('SMARTY_RIGHT_DELI'),
            'SMARTY_DATA_CONFIG'     => $this -> cfg_get('SMARTY_DATA_CONFIG'),
            'SMARTY_DATA_CACHE'      => $this -> cfg_get('SMARTY_DATA_CACHE'),
            'SMARTY_TPL'             => $this -> cfg_get('SMARTY_TPL'),
            'SMARTY_DATA_TPL_C'      => $this -> cfg_get('SMARTY_DATA_TPL_C')
        ));
    }

    /**
     * @name: cfg_session
     * @description: 配置SESSION会话
     * @scope: private
     * @return: void
    **/
    private function cfg_session(){
        get_mod('sess') -> set_conf(array(
            'SESS_TYPE'         => $this -> cfg_get('SESS_TYPE'),
            'SESS_PATH'         => $this -> cfg_get('SESS_PATH'),
            'SESS_NAME'         => $this -> cfg_get('SESS_NAME'),
            'SESS_TABLE_NAME'   => $this -> cfg_get('SESS_TABLE_NAME'),
            'SESS_ID_NAME'      => $this -> cfg_get('SESS_ID_NAME'),
            'SESS_DATA_NAME'    => $this -> cfg_get('SESS_DATA_NAME'),
            'SESS_TIME_NAME'    => $this -> cfg_get('SESS_TIME_NAME'),
            'SESS_IP_NAME'      => $this -> cfg_get('SESS_IP_NAME'),
            'SESS_U_ID_NAME'    => $this -> cfg_get('SESS_U_ID_NAME'),
            'SESS_U_NAME'       => $this -> cfg_get('SESS_U_NAME'),
            'MAX_LIFE_TIME'     => $this -> cfg_get('MAX_LIFE_TIME'),
            'SESS_MYSQLI_MARK'  => $this -> cfg_get('SESS_MYSQLI_MARK'),
            'SESS_MYSQLI_CONF'  => array(
                'MYSQLI_HOST'     => $this -> cfg_get('SESS_MYSQLI_CONF,MYSQLI_HOST'),
                'MYSQLI_USER'     => $this -> cfg_get('SESS_MYSQLI_CONF,MYSQLI_USER'),
                'MYSQLI_PASS'     => $this -> cfg_get('SESS_MYSQLI_CONF,MYSQLI_PASS'),
                'MYSQLI_DBNAME'   => $this -> cfg_get('SESS_MYSQLI_CONF,MYSQLI_DBNAME'),
                'MYSQLI_PORT'     => $this -> cfg_get('SESS_MYSQLI_CONF,MYSQLI_PORT'),
                'MYSQLI_TABLE_PREFIX' => $this -> cfg_get('SESS_MYSQLI_CONF,MYSQLI_TABLE_PREFIX')
            )
        ));
    }

    /**
     * @name: cfg_cache
     * @description: 配置CACHE
     * @scope: private
     * @return: void
    **/
    private function cfg_cache(){
        get_mod('cache') -> set_conf(array(
            'CACHE_TYPE' => $this -> cfg_get('CACHE_TYPE'),
            'MEMCACHED_DATA' => array(
                'MEMCACHE_HOST' => $this -> cfg_get('MEMCACHED_DATA,MEMCACHE_HOST'),
                'MEMCACHE_PORT' => $this -> cfg_get('MEMCACHED_DATA,MEMCACHE_PORT'),
                'MEMCACHE_TIME_OUT' => $this -> cfg_get('MEMCACHED_DATA,MEMCACHE_TIME_OUT'),
                'MEMCACHE_EXPIRE' => $this -> cfg_get('MEMCACHED_DATA,MEMCACHE_EXPIRE'),
                'MEMCACHE_FLAG' => $this -> cfg_get('MEMCACHED_DATA,MEMCACHE_FLAG')
            )
        ));
    }

    /**
     * @name: cfg_mysql
     * @description: 配置MYSQL
     * @scope: private
     * @return: void
    **/
    private function cfg_mysql(){
        $mysql = $this -> cfg_get('MYSQL');
        if(is_foreach($mysql)) foreach($mysql as $mark => $conf){
            if(isset($conf['MYSQLI_HOST']) || isset($conf['MYSQLI_WRITE']) || isset($conf['MYSQLI_READ'])) get_mod('mysqli', $mark) -> set_conf($conf);
        }
    }

    /**
     * @name: run
     * @description: 程序运行
     * @scope: public
     * @return: string
    **/
    public function run(){
        //启动SESSION
        if(!SESS_AUTO_START) get_mod('sess') -> open($this -> cur_s);
        //判定登录
        if(!$this -> check_login($this -> cur_a)) $this -> show_error('LOGIN');
        //判定权限
        if(!$this -> check_purview($this -> cur_a)) $this -> show_error('PURVIEW');
        //载入模块
        if(!$this -> load_mod()) $this -> show_error('MODLOAD');
    }

    /**
     * @name: check_login
     * @description: 检测登录
     * @scope: private
     * @param: string 本次请求
     * @return: boolean
    **/
    private function check_login($key){
        $no_login_list = array('sys-login', 'sys-login-login', 'sys-login-logout');
        if(in_array($key, $no_login_list)) return TRUE;
        if(get_init('admin') -> is_login()) return TRUE;
        return FALSE;
    }

    /**
     * @name: check_purview
     * @description: 检测权限
     * @scope: private
     * @param: string 被检测权限KEY
     * @return: boolean
    **/
    private function check_purview($key){
        $no_purview_list = array('sys-login', 'sys-login-login', 'sys-login-logout', 'sys-index', 'sys-get_menu');
        if(in_array($key, $no_purview_list)) return TRUE;
        if(get_init('admin') -> check_purview($key)) return TRUE;
        return FALSE;
    }

    /**
     * @name: load_mod
     * @description: 加载模块
     * @scope: private
     * @return: boolean
    **/
    private function load_mod(){
        $get_data = $this -> get_data();
        $inc_url = SYSTEM_MOD.$this -> mod.'/'.$this -> mods.'.php';
        if(file_exists($inc_url)){
            include_once($inc_url);
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * @name: show_error
     * @description: 显示运行错误结果页面
     * @scope: private
     * @param: string 错误类型
     * @return: void
    **/
    private function show_error($type){
        switch($type){
            case 'MOD': {  //模块不允许
                
                break;
            }
            case 'LOGIN': {  //需要登录
                if(false){
                    $res = array('r' => 0, 'm' => '本次登录超时,请重新登录.', 'd' => array());
                    die(json_encode($res));
                }else{
                    get_mod('smarty') -> show('sys/login.html');
                }
                break;
            }
            case 'PURVIEW': {   //权限不允许
                $res = array('r' => 0, 'm' => '本次请求权限不足,请联系管理员.', 'd' => array());
                die(json_encode($res));
                break;
            }
            case 'MODLOAD': {  //模块加载不允许
                
                break;
            }
            default: {  //其他错误
                
            }
        }
    }

    /**
     * @name: set_mysql
     * @description: 获取表字段
     * @scope: public
     * @param: string mysql标签
     * @return: void
    **/
    public function set_mysql($mark){
        $this -> mod_mysqli = get_mod('mysqli', $mark);
        return $this;
    }

    /**
     * @name: get_table_fields
     * @description: 获取表字段
     * @scope: public
     * @param: string 表名
     * @param: boolean 是否返回数据 default[TRUE]
     * @return: array|string
    **/
    public function get_table_fields($table, $is_array=TRUE){
        $table_fields = $this -> mod_mysqli -> get_table_fields($table);
        $field_array = array();
        $field_string = '*';
        if(is_foreach($table_fields)){
            foreach($table_fields as $key => $val){
                $field_array[] = $val['Field'];
            }
            $field_string = implode(',', $field_array);
        }
        if($is_array) return $field_array;
        return $field_string;
    }

    /**
     * @name: get_sql_insert
     * @description: 获取insert sql 语句
     * @scope: public
     * @param: array 数据(array('field' => $value))
     * @param: string 表名
     * @return: string
    **/
    public function get_sql_insert($data, $table){
        $return = '';
        if(!is_foreach($data) || $table == '') return $return;
        $return = 'INSERT INTO '.$this -> mod_mysqli -> get_table_name($table).' SET ';
        foreach($data as $key => $val){
            $return .= $key.'=\''.$val.'\',';
        }
        return rtrim($return, ',');
    }

    /**
     * @name: get_sql_update
     * @description: 获取update sql 语句
     * @scope: public
     * @param: array 数据(array('field' => $value))
     * @param: string 表名
     * @param: string where 条件
     * @return: string
    **/
    public function get_sql_update($data, $table, $where){
        $return = '';
        if(!is_foreach($data) || $table == '' || $where == '') return $return;
        $return = 'UPDATE '.$this -> mod_mysqli -> get_table_name($table).' SET ';
        foreach($data as $key => $val){
            $return .= $key.'=\''.$val.'\',';
        }
        $tmp_where = ' WHERE '.preg_replace_callback('/^AND/i',function($match){return '';}, trim($where));
        return rtrim($return, ',').$tmp_where;
    }
}
?>