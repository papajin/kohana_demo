/**
 * Article list controller.
 * Extends Pages list controller
 */
page.article_children = function(){
    var parent = $(this).attr('name');

    if ( !$('#'+parent).length ) {
        var children = $(this).data('data');
        var $table = $('table.table').clone();
        var $tbody = $table.find('tbody');
        $tbody.html('');

        $.each(children, function(ind, article){
            var $tr = $('<tr/>',{"data-id":article.id});
            $('<td/>', {width:40,html:article.id}).appendTo($tr);
            $('<td/>', {html:'<a href="/admin/article/edit/'+article.id+'">'+article.title+'</a>'
            +(article.tags.length ? '<span rel="tooltip" title="'+article.tags+'" class="icon-tags pull-xs-right"></span>' : '')}).appendTo($tr);
            $('<td/>', {width:150,html:article.alias}).appendTo($tr);
            $('<td/>', {width:145,class:"text-center",html:'<div class="btn-group btn-group-sm"><a href="/articles/'+article.alias+'.html" title="' + i18n.view + '" class="btn btn-secondary" target="_blank"><i class="icon-search"></i> </a>\n\
                                                                <a href="/admin/article/edit/'+article.id+'" title="' + i18n.edit + '" class="btn btn-secondary"><i class="icon-pencil"></i> </a>\n\
                                                                <a href="#" title="' + i18n.delete_cache + '" onclick="page.delete_cache()" class="btn btn-secondary"><i class="icon-arrows-cw"></i> </a></div>'}).appendTo($tr);
            $tr.appendTo($tbody);
        });
        
        _modal.onClose = function(){};
        _modal.frame.modal.id = parent;
        _modal.init({
            body:{html:$table[0]},
            footer:{html:'<button class="btn" data-dismiss="modal">' + i18n.close + '</button> <button class="btn btn-primary" onclick="page.save_articles_order()">' + i18n.save + '</button>'}
        });
        $table.parents('.modal-dialog').addClass('modal-lg');

        $table.find('[rel = "tooltip"]').tooltip();
        $table.find('.sortable').sortable({
            group: 'modal',
            containerSelector: 'tbody',
            itemSelector: 'tr',
            placeholder: '<i class="icon-right text-danger"></i>',
            handle: 'tr td:not(:last-child)'
        });
    }
    else {
        $('#'+parent).modal('show');
    }
};

page.save_articles_order = function () {
    var sort_table = $(event.currentTarget).parents('.modal-content').find('.sortable');
    var data = sort_table.sortable('serialize').get();
    var order = [];

    $.each(data, function(){
        $.each(this, function(key, val){
            order[val.id] = key;
        });
    });

    page.run_ajax('/admin/ajax/save_order', {param: JSON.stringify( order )});
};

/* extra functions to supplement parent init */
page.extend = function(){
    $('[name="category_id"]').change(function(){
        window.location = '/admin/article/index/'+$(this).find('option:selected').val();
    });
    $('button[name ^= "children_"]').click(page.article_children);
};