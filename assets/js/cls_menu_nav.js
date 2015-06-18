if(typeof(CLS_MENU_NAV) == 'undefined'){
var CLS_MENU_NAV = {
    top_id : '#nav_top',
    side_id: '#nav_left',
    nav  : null,
    init : function(_nav){ this.nav = _nav; this.set_top(); },
    set_top : function(){
        var _k = -1;
        if(typeof(this.nav.top) != 'undefined'){
            var _html = '';
            for(var i in this.nav.top){
                if(_k < 1) _k = this.nav.top[i].mn_id;
                if(typeof(this.nav.top[i].list) == 'undefined'){
                    _html += '<li mn_id="'+this.nav.top[i].mn_id+'"><a href="javascript: CLS_MENU_NAV.set_left('+this.nav.top[i].mn_id+');">'+this.nav.top[i].mn_name+'</a></li>';
                }else{
                    _html += '<li mn_id="'+this.nav.top[i].mn_id+'" class="dropdown"><a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">'+this.nav.top[i].mn_name+'&nbsp;<i class="ace-icon fa fa-angle-down bigger-110"></i></a><ul class="dropdown-menu dropdown-light-blue dropdown-caret">';
                    for(var j in this.nav.top[i].list) _html += '<li mn_id="0"><a href="javascript:CLS_MENU_NAV.set_left('+this.nav.top[i].list[j].mn_id+','+this.nav.top[i].mn_id+');"><i class="ace-icon fa '+this.nav.top[i].list[j].mn_icon+' bigger-110 blue"></i> '+this.nav.top[i].list[j].mn_name+'</a></li>';
                    _html += '</ul></li>';
                }
            }
            $(this.top_id).html(_html); //top
        }
        if(_k >= 0) this.set_left(_k);
    },
    set_left: function(_i,_j){
        if(typeof(this.nav.left[_i]) != 'undefined'){
            var _mnid = typeof(_j) != 'undefined' ? _j : _i;
            var _mnobj = $('li[mn_id='+_mnid+']', $(this.top_id));
            if(_mnobj.length > 0){
                $('li[mn_id!=0]', $(this.top_id)).removeClass('open');
                _mnobj.addClass('open');
            }
            var _html = nav_url = '';
            var _k = mn_id = 0;
            for(var i in this.nav.left[_i]){
                if(_k == 0) mn_id= this.nav.left[_i][i].mn_id;
                if(typeof(this.nav.left[_i][i].list) == 'undefined'){
                    if(_k == 0) nav_url= this.nav.left[_i][i].mn_url;
                    _html += '<li mn_id="'+this.nav.left[_i][i].mn_id+'"><a href="javascript:CLS_MENU_NAV.set_url(\''+this.nav.left[_i][i].mn_url+'\','+this.nav.left[_i][i].mn_id+');"><i class="menu-icon fa '+ this.nav.left[_i][i].mn_icon +'"></i><span class="menu-text">'+ this.nav.left[_i][i].mn_name +'</span></a><b class="arrow"></b></li>';
                }else{
                    _html += '<li><a href="javascript:;" class="dropdown-toggle"><i class="menu-icon fa '+ this.nav.left[_i][i].mn_icon +'"></i><span class="menu-text">'+ this.nav.left[_i][i].mn_name +'</span><b class="arrow fa fa-angle-down"></b></a><b class="arrow"></b><ul class="submenu nav-show">';
                    var jj = 0;
                    for(var j in this.nav.left[_i][i].list){
                        if(_k == 0 && jj == 0){ nav_url= this.nav.left[_i][i].list[j].mn_url; jj = 1;}
                        _html += '<li><a href="javascript:;" onclick="javascript: CLS_MENU_NAV.set_url(\''+this.nav.left[_i][i].list[j].mn_url+'\','+this.nav.left[_i][i].mn_id+');"><i class="menu-icon fa fa-caret-right"></i> '+ this.nav.left[_i][i].list[j].mn_name +'</a><b class="arrow"></b></li>';
                    }
                    _html += '</ul></li>';
                }
                ++_k;
            }
            $(this.side_id).html(_html);  //left
            if(nav_url != '' && mn_id > 0) CLS_MENU_NAV.set_url(nav_url,mn_id);
        }
    },
    set_url: function(_url,_mnid){
        if(typeof(_url) == 'undefined' || _url == ''){
            CLS_NOTICE.show_tip({text: "操作无效!",timeout: 3000});
            return;
        }
        CLS_GLOBAL.reset().set_url(_ADMIN_FILE).
        add_data('a', _url).
        set_call(function(_res){
            CLS_NOTICE.hide_loading();
            if(typeof(_res.r) != 'undefined' && _res.r == 0){
                CLS_NOTICE.show_tip({text: (typeof(_res.m) != 'undefined' ? _res.m:"操作异常!"),timeout: (typeof(_res.t) != 'undefined' ? _res.t:3000)});
            }else{
                var _mnobj = $('li[mn_id='+_mnid+']', $(CLS_MENU_NAV.side_id));
                if(_mnobj.length > 0){
                    $('li[mn_id!=0]', $(CLS_MENU_NAV.side_id)).removeClass('active');
                    _mnobj.addClass('active');
                }
                if(typeof(_res.d.nav) != 'undefined'){
                    var _nav_path = _res.d.nav;
                    var _html = '';
                    for(var i in _nav_path){
                        _html += '<li>'+_nav_path[i]+'</li>';
                    }
                    $('#nav_path').html(_html);
                }
                    $('#action_adm').remove();
                if(typeof(_res.d.nav_adm) != 'undefined'){
                    var _html = '<div class="pull-right" id="action_adm"><span adm="'+ _res.d.nav_adm.adm +'" role="button">'+ _res.d.nav_adm.name +'</span></div>';
                    $('#nav_path').after(_html);
                }
                if(typeof(_res.d.content) != 'undefined') $('#main_content').html(_res.d.content);
            }
        }).
        set_call_bf(function(_res){CLS_NOTICE.show_loading();}).
        set_call_er(function(_res){
            CLS_NOTICE.hide_loading();
            CLS_NOTICE.show_tip({text:(typeof(_res.m) != 'undefined' ? _res.m:"操作异常!"),timeout: 3000});
        }).send();
    }
};
}