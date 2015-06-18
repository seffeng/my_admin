if(typeof(CLS_LOGIN) == 'undefined'){
var CLS_LOGIN = {
    btn_id  : 'button[adm="login"]', /*登录按钮ID*/
    user_id : '#login-box input[name="username"]',  /*账号框ID*/
    pass_id : '#login-box input[name="userpass"]',  /*密码框ID*/
    username: '',   /*账号值*/
    userpass: '',   /*密码值*/
    /*初始化*/
    init  : function(){
        this.login();
        this.key_listen();
    },
    /*设置用户名值*/
    set_username : function(){
        this.username = $(this.user_id).val();
    },
    /*设置密码值*/
    set_userpass : function(){
        this.userpass = $(this.pass_id).val();
    },
    /*检测合法性*/
    check : function(){
        this.set_username();
        if(this.username == ''){
            CLS_NOTICE.show_tip('请输入账号');
            $(this.user_id).focus();
            return false;
        }
        this.set_userpass();
        if(this.userpass == ''){
            $(this.pass_id).focus();
            CLS_NOTICE.show_tip('请输入密码');
            return false;
        }
        return true;
    },
    /*登录操作*/
    login : function(){
        $(this.btn_id).on('click', function(){
            if(CLS_LOGIN.check()){
                var this_btn = $(this).button('loading');
                CLS_GLOBAL.reset().set_url(_ADMIN_FILE).
                add_data('a', 'sys-login-login').
                add_data('username', (CLS_LOGIN.username)).
                add_data('userpass', (CLS_LOGIN.userpass)).
                set_call(function(_res){
                    if(typeof(_res.r) != 'undefined' && _res.r == 1){
                        window.location.href = _res.u;
                        return false;
                    }
                    this_btn.button('reset');
                    CLS_NOTICE.show_tip((typeof(_res.m) != 'undefined' ? _res.m:"操作异常!"));
                    CLS_NOTICE.hide_loading();
                }).
                set_call_bf(function(_res){CLS_NOTICE.show_loading();}).
                set_call_er(function(_res){this_btn.button('reset'); CLS_NOTICE.hide_loading(); CLS_NOTICE.show_tip({text:(typeof(_res.m) != 'undefined' ? _res.m:"操作异常!"),timeout: 5000});}).send();
            }
        });
    },
    /*监听账号密码框的键盘事件*/
    key_listen: function(){
        $('input').keyup(function(e){
            if(e.keyCode == 13){
                $(CLS_LOGIN.btn_id).click();
            }
        });
    },
};
CLS_LOGIN.init();
}