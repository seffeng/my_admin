if(typeof CLS_REQUEST == 'undefined'){
var CLS_REQUEST = {
    s : '',
    l : '',
    init : function(_s, _l){ this.s = _s; this.l = _l; },
    gets : function(){ return this.s; },
    request: function(_call, _url, _data, _dtype, _type, _call_er, _call_bf){
        _type = _type == 'GET' ? 'GET' : 'POST';
        _dtype = (typeof(_dtype) == 'string' && _dtype != '') ? _dtype : 'json';
        if(this.s != '') _data['s'] = this.s;
        if(this.l != '') _data['l'] = this.l;
        $.ajax({url:_url,data:_data,type:_type,cache:false,dataType:'json',success: function(_res){if(typeof(_call)=='function')_call.call(this, _res);},error: function(_res){if(typeof(_call_er)=='function')_call_er.call(this, _res);},beforeSend: function(_res){if(typeof(_call_bf)=='function')_call_bf.call(this, _res);}});
    }
};
CLS_REQUEST.init('','');
}