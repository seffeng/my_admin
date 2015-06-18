if(typeof(CLS_GLOBAL) == 'undefined'){
var CLS_GLOBAL = {
    _url  : null,
    _call : null,
    _call_er : null,
    _call_bf : null,
    _data  : {},
    _dtype : 'json',
    _type  : 'POST',
    set_url  :    function(_url){ this._url = _url; return this; },
    set_call :    function(_call){ this._call = _call; return this; },
    set_call_er : function(_call_er){ this._call_er = _call_er; return this; },
    set_call_bf : function(_call_bf){ this._call_bf = _call_bf; return this; },
    reset_data  : function(_data){ this._data = {}; return this; },
    set_data :    function(_data){ this._data = _data; return this; },
    add_data :    function(_k, _v){ this._data[_k] = _v; return this; },
    del_data :    function(_k){ for(var a in this._data){ if(a == _k){ delete this._data[a]; }} return this; },
    set_dtype :   function(_dtype){ this._dtype = _dtype; return this; },
    set_type  :   function(_type){ this._type = _type; return this; },
    send  : function(){ CLS_REQUEST.request(this._call, this._url, this._data, this._dtype, this._type, this._call_er, this._call_bf); return this;},
    reset : function(){
        this._url = null;
        this._call = null;
        this._call_er = null;
        this._call_bf = null;
        this._data = {};
        this._dtype = 'json';
        this._type = 'POST';
        return this; 
    },
};
}