if(typeof(CLS_FORM) == 'undefined'){
var CLS_FORM = {
    req_url : '',
    add_url : '',
    del_url : '',
    edit_url: '',
    init : function(obj){
        if(typeof(obj.req) != 'undefined') this.req_url = obj.req;
        if(typeof(obj.add) != 'undefined') this.add_url = obj.add;
        if(typeof(obj.del) != 'undefined') this.del_url = obj.del;
        if(typeof(obj.edit) != 'undefined') this.edit_url = obj.edit;
        this.pg_select();
        this.pg();
        this.refresh();
        this.add();
        this.sort();
        this.edit();
        this.del();
        this.list();
        this.ok();
        this.key_submit();
    },
    to_list : function(_url, _pg, _psize, _sort, _id){
        if(typeof(_url) == 'undefined' || _url.length < 1) _url = CLS_FORM.req_url;
        if(typeof(_pg) == 'undefined' || _pg < 1) _pg = $('#pg_select option:selected').val();
        if(typeof(_sort) == 'undefined' || _sort.length < 1) _sort = $('#pg_sort').val();
        CLS_GLOBAL.reset().set_url(_ADMIN_FILE).
        add_data('a', _url).
        add_data('pg', _pg).
        add_data('sort', _sort).
        add_data('id', _id).
        set_call(function(_res){
            CLS_NOTICE.hide_loading();
            if(typeof(_res.r) != 'undefined' && _res.r == 1){
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
            }else{
                CLS_NOTICE.show_tip({text:(typeof(_res.m) != 'undefined' ? _res.m:"操作异常!"),timeout: 5000});
            }
        }).
        set_call_bf(function(_res){CLS_NOTICE.show_loading();}).
        set_call_er(function(_res){CLS_NOTICE.hide_loading();CLS_NOTICE.show_tip({text:(typeof(_res.m) != 'undefined' ? _res.m:"操作异常!"),timeout: 5000});}).send();
    },
    pg_select : function(){ $('#pg_select').on('change', function(){ CLS_FORM.to_list(CLS_FORM.req_url, $(this).val()); }); },
    pg : function(){ $('a[adm="pg"]').on('click', function(){ if(!$(this).parent().hasClass('disabled')) CLS_FORM.to_list(CLS_FORM.req_url, $(this).attr('pg')); }); },
    refresh : function(){ $('span[adm="refresh"]').on('click', function(){ CLS_FORM.to_list(CLS_FORM.req_url); }); },
    add  : function(){ $('span[adm="add"]').on('click', function(){ CLS_FORM.to_list(CLS_FORM.add_url); }); },
    sort : function(){
        $('th', $('tr[role="row"]')).on('click', function(){
            var _sort = $(this).attr('sort');
            if($(this).hasClass('sorting_asc')){
                _sort += ',DESC';
            }else if($(this).hasClass('sorting_desc')){
                _sort += ',ASC';
            }else if($(this).hasClass('sorting')){
                _sort += ',ASC';
            }else{
                return ;
            }
            CLS_FORM.to_list(CLS_FORM.req_url, 0, 0, _sort);
        });
    },
    list : function(){
        $('span[adm="list"]').on('click', function(){
            CLS_FORM.to_list();
        });
    },
    edit : function(){
        $('span[adm="edit"]').on('click', function(){
            var _id = parseInt($(this).attr('data'), 10);
            CLS_FORM.to_list(CLS_FORM.edit_url, '', '', '', _id);
        });
    },
    del : function(){
        $('span[adm="del"]').on('click', function(){
            var _id = $(this).attr('data');
            $('#modal-container input[name="id"]').val(_id)
            $('#modal-container input[name="url"]').val(CLS_FORM.del_url)
            $('#modal_text').text('确定要删除?');
            $('#modal-container').modal('show');
        });
    },
    ok : function(){
        $('#dialog_ok').off().on('click', function(){
            var _id = parseInt($('#modal-container input[name="id"]').val(), 10);
            var _url = $('#modal-container input[name="url"]').val();
            CLS_GLOBAL.reset().set_url(_ADMIN_FILE).
            add_data('a', _url).
            add_data('id', _id).
            set_call(function(_res){
                if(typeof(_res.r) != 'undefined' && _res.r == 1){
                    CLS_NOTICE.show_tip({text:(typeof(_res.m) != 'undefined' ? _res.m:"操作成功!"),timeout: 5000, type:'success'});
                    CLS_FORM.to_list(CLS_FORM.req_url);
                }else{
                    CLS_NOTICE.show_tip({text:(typeof(_res.m) != 'undefined' ? _res.m:"操作异常!"),timeout: 5000});
                }
                $('#modal-container').modal('hide');
            }).
            set_call_bf(function(_res){CLS_NOTICE.show_loading();}).
            set_call_er(function(_res){CLS_NOTICE.hide_loading();$('#modal-container').modal('hide');CLS_NOTICE.show_tip({text:(typeof(_res.m) != 'undefined' ? _res.m:"操作异常!"),timeout: 5000});}).send();
        });
    },
    key_submit : function(){
        $('#main_content input').on('keyup', function(e){
            if(e.ctrlKey && e.keyCode == 13){
                $('button[adm="submit"]').click();
            }
        });
    }
};
}