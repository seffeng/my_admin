if(typeof(CLS_NOTICE) == 'undefined'){
var CLS_NOTICE = {
    id      : '#notice',
    name    : 'CLS_NOTICE',
    version : '1.0',
    data    : {type : 'danger', text : 'error', timeout : 3000},   /*type提示类型[danger|success|info|warning]*/
    set_data : function(obj){
        this.reset();
        if(typeof(obj) == 'object'){
            if(typeof(obj.type) != 'undefined') this.data.type = obj.type;
            if(typeof(obj.timeout) != 'undefined') this.data.timeout = obj.timeout;
            if(typeof(obj.text) != 'undefined') this.data.text = obj.text;
        }else if(typeof(obj) == 'string'){
            this.data.text = obj;
        }
    },
    show_tip : function(obj){
        this.set_data(obj);
        var rand_id = Math.random();
        var _html = '<div class="alert alert-'+ this.data.type +' fade in out" rand="'+ rand_id +'"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><span class="text">'+ this.data.text +'</span></div>';
        $(this.id).append(_html);
        setTimeout("CLS_NOTICE.hide_tip(\'"+ rand_id +"\')", this.data.timeout);
    },
    hide_tip : function(rand_id){
        $(this.id +' .alert[rand="'+ rand_id +'"]').slideUp('normal', function(){$(this.id +' .alert[rand="'+ rand_id +'"]').remove();});
    },
    reset : function(){
        this.data = {type : 'danger', text : 'error', timeout : 3000};
    },
    show_loading : function(){
        $('.position-relative').append('<div class="ajax-loading-overlay"><i class="ajax-loading-icon fa fa-spin fa-spinner fa-2x orange"></i> </div>');
    },
    hide_loading : function(){
        $('.ajax-loading-overlay').remove();
    },
}
}