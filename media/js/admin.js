/**
*  Admin area common js
 */
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

if ( typeof(noty) !== 'undefined' )
{
    $.noty.defaults.layout='center';
    var ensure_array = function (a){return ($.isArray(a)||$.isPlainObject(a))?a:[a];},
        error = function (a){a=ensure_array(a);$.each(a, function(){noty({text:this,type:'error'});});},
        success = function (a){a=ensure_array(a);$.each(a, function(){noty({text:this,type:'success'});});},
        info = function (a){a=ensure_array(a);$.each(a, function(){noty({text:this,type:'information'});});},
        warning = function (a){a=ensure_array(a);$.each(a, function(){noty({text:this,type:'warning'});});},
        alert = function (a){a=ensure_array(a);$.each(a, function(){noty({text:this});});},

        _sure = function ( text, callback, deny ) {
            callback = typeof callback !== 'undefined' ? callback : function(){return false;};
            var res = false;
            var respond = function(){
                if (res) callback();
            };

            noty({
                text: text,
                type: 'confirm',
                dismissQueue: false,
                layout: 'center',
                theme: 'defaultTheme',
                buttons: [
                    {
                        addClass: 'btn btn-danger',
                        text: 'ok',
                        onClick: function($noty) {
                            // this = button element
                            // $noty = $noty element
                            res = true;
                            $noty.close();
                        }
                    },
                    {
                        addClass: 'btn btn-secondary',
                        text: i18n.cancel,
                        onClick: function($noty) {
                            $noty.close();
                            if ( typeof deny === 'function' )
                                deny();
                        }
                    }
                ],
                callback: {
                    onClose: respond
                }
            });
        };
}
var _timer = {
        node: $('<div/>',{id:"loading",class:"text-xs-center",html:'<i class="lg icon-sun animated spin infinite text-warning"></i>'}),
        up: function() {this.node.appendTo($('body'));},
        over: function() {$('#loading').remove();}
    },

    _modal = {
    frame: {modal:{class: "modal fade", id:"myModal"},
            dialog:{class: "modal-dialog"},
            content:{class: "modal-content"}},
    options: {},
    defaults:{header: {class: "modal-header", html:'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'},
              body:   {class: "modal-body",html:''},
              footer: {class: "modal-footer",html:''}},
    onClose: function () {this.remove();},
    init: function(options){
        $.extend(true,this.options,this.defaults,options);
        $.each(this.frame, function(key, settings){
            _modal[key] = $('<div/>', settings);
        });
        $.each(this.options, function(key, settings){
            if(settings.html) $('<div/>', settings).appendTo(_modal.content);
        });
        
        this.content.appendTo(this.dialog);
        this.dialog.appendTo(this.modal);
        this.modal.modal('show');
        this.modal.on('hidden.bs.modal', this.onClose);
        this.bind_click();
        return this;
    },
    bind_click: function(){
        this.modal.find('[data-click]').click(function(){window[$(this).data('click')]();});
    }
};

$.fn.filterByData = function(prop, val) {
    return this.filter(
        function() { return $(this).data(prop)===val; }
    );
};

$(document).ready(function(){

    /* Initial messages from hidden #alerts */
    var mes = $('#alerts').val();
    if (mes){
        $.each( $.parseJSON(mes), function(i, v){
            noty({ text: v.text, type: v.type });
        });
    }
    $('a[href*="/delete/"]').click(function(){
        event.preventDefault();
        var href = $(this).attr('href');
		_sure(i18n.sure_delete, function(){window.location.href = href;});
    });
});