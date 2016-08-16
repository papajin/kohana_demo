/**
 * Article edit js
 */
$(function() {
    var self = this;

    /**
     * Update of parent article dropdown list.
     * @param {array} opts -  array of articles from self.get_cat_articles().
     * @returns void
     */
    self.update_parents_options = function(opts) {
        var s = $('#parent_id');
        s.removeAttr('disabled');
        var options = '<option value="0" selected="selected">' + i18n.choose + '</option>';
        for (var k in opts) {
            if (!opts.hasOwnProperty(k)) continue;
            options += '<option value="'+k+'">'+opts[k]+'</option>';
        }
        s.html(options);
    };

    /**
     * Getting category articles by category id
     */
    self.get_cat_articles = function(id) {
        _timer.up();
        $.ajax({
            url: '/admin/ajax/get_cat_articles',
            data: {param: id},
            type: "POST"
        }).done(function(response) {
            response = $.parseJSON(response);
            if(response.res)
                self.update_parents_options(response.data);
        })
            .fail(function(jqXHR, textStatus, errorThrown) {
                error(errorThrown);
            })
            .always(_timer.over);
    };

    self.move_tag = function($tag) {
        var remove = $tag.parent().is('#attached_tags');
        var url = remove ? '/admin/ajax/remove_tag' : '/admin/ajax/add_tag';

        _timer.up();
        $.ajax({
            url: url,
            data: {param: $tag.html(), article_id: $('label[for="title"] > i').html()},
            type: "POST"
        }).done(function(response) {
            response = $.parseJSON(response);
            if(response.res)
                remove ? self.remove_tag($tag) : self.add_tag($tag);
            else
                error(response.mes);
        })
            .fail(function(jqXHR, textStatus, errorThrown) {
                error(errorThrown);
            })
            .always(_timer.over);
    };

    self.remove_tag = function($tag) {
        $tag.detach().appendTo("#source_tags");
        $('#source_tags > span').sortElements(function(a, b){
            return $(a).text() > $(b).text() ? 1 : -1;
        });
    };
    
    self.add_tag = function($tag) {
        $tag.detach().appendTo("#attached_tags");
        $('#attached_tags > span').sortElements(function(a, b){
            return $(a).text() > $(b).text() ? 1 : -1;
        });
    };

    self.get_tag = function($tag) {
        _timer.up();
        $.ajax({
            url: '/admin/ajax/get_tag',
            data: {param: $tag.html()},
            type: "POST"
        }).done(function(response) {
            response = $.parseJSON(response);
            if(response.res) {
                $tag.addClass('edited');
                self.edit_tag(response.data);
            }
            else
                error(response.mes);
        })
            .fail(function(jqXHR, textStatus, errorThrown) {
                error(errorThrown);
            })
            .always(_timer.over);
    };

    self.edit_tag = function(tag) {
        var tag_form = '<div class="form-group"><label title="' + i18n.tag_edit_title + '" for="tag_name">' + i18n.name + '</label><input type="text" id="tag_name" value="'+tag.name+'" class="form-control"></div>';
        tag_form += '<div class="form-group"><label title="' + i18n.slug_edit_title + '" for="tag_slug">' + i18n.slug + '</label><input type="text" id="tag_slug" value="'+tag.slug+'" class="form-control"></div>';
        tag_form += '<div class="form-group"><label for="tag_slug">' + i18n.description + '</label><textarea id="tag_description" rows="3" class="form-control">'+tag.description+'</textarea></div>';
        tag_form += '<input id="term_id" type="hidden" value="'+tag.term_id+'" />';
        _modal.init({body:{html:tag_form},
            footer:{html:'<button class="btn" data-dismiss="modal">' + i18n.close + '</button> <button class="btn btn-primary" data-click="_save_tag">' + i18n.save + '</button>'}});

        _modal.modal.on('hide.bs.modal', function(){$('.edited').removeClass('edited');});
    };
    
    /* Bindings */

    $('select[name="category_id"]').change(function(){
        self.get_cat_articles($(this).find('option:selected').val());
    });

    /* Handling tags: onclick adds/removes tag; dblclick opens edit tag modal*/
    var timer = null, DELAY = 300;
    $('#cloud .btn').click(function (e) {
        var $this = $(this);
        if ($this.hasClass('clicked')){
            self.get_tag( $this );
            $this.removeClass('clicked');
            clearTimeout(timer);
        }
        else{
            $this.addClass('clicked');

            timer = setTimeout(function() {

                self.move_tag( $this );
                $this.removeClass('clicked');

            }, DELAY);
        }//end of else
    });
  });


/**
 * jQuery.fn.sortElements
 * --------------
 * @param Function comparator:
 *   Exactly the same behaviour as [1,2,3].sort(comparator)
 *
 * @param Function getSortable
 *   A function that should return the element that is
 *   to be sorted. The comparator will run on the
 *   current collection, but you may want the actual
 *   resulting sort to occur on a parent or another
 *   associated element.
 *
 *   E.g. $('td').sortElements(comparator, function(){
 *      return this.parentNode;
 *   })
 *
 *   The <td>'s parent (<tr>) will be sorted instead
 *   of the <td> itself.
 */
jQuery.fn.sortElements = (function(){

    var sort = [].sort;

    return function(comparator, getSortable) {

        getSortable = getSortable || function(){return this;};

        var placements = this.map(function(){

            var sortElement = getSortable.call(this),
                parentNode = sortElement.parentNode,

            // Since the element itself will change position, we have
            // to have some way of storing its original position in
            // the DOM. The easiest way is to have a 'flag' node:
                nextSibling = parentNode.insertBefore(
                    document.createTextNode(''),
                    sortElement.nextSibling
                );

            return function() {

                if (parentNode === this) {
                    throw new Error(
                        "You can't sort elements if any one is a descendant of another."
                    );
                }

                // Insert before flag:
                parentNode.insertBefore(this, nextSibling);
                // Remove flag:
                parentNode.removeChild(nextSibling);

            };

        });

        return sort.call(this, comparator).each(function(i){
            placements[i].call(getSortable.call(this));
        });

    };

})();

var _save_tag = function() {
    var self = this;
    self.tag = {
        term_id: $('#term_id').val(),
        name: $('#tag_name').val(),
        slug:$('#tag_slug').val(),
        description:$('#tag_description').val()
    };

    if ( !self.tag.name ) {
        error(i18n.tag_name_empty);
    }
    else {
        _timer.up();
        $.ajax({
            url: '/admin/ajax/save_tag',
            data: {param: self.tag},
            type: "POST"
        }).done(function(response) {
            response = $.parseJSON(response);
            if(response.res)
                self.fix_tag();
            else
                error(response.mes);
        })
            .fail(function(jqXHR, textStatus, errorThrown) {
                error(errorThrown);
            })
            .always(_timer.over);
    }

    self.fix_tag = function(){
        $('#cloud .btn.label.edited').html(self.tag.name);
        _modal.modal.modal('hide');
    };
};