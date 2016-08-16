/**
 * Pages list
 */
$(function() {
    $('#description').keyup(function(){
        $('#symbols_cnt').text($(this).val().length);
    });

    CKEDITOR.config.language = 'uk';
    CKEDITOR.config.allowedContent = true;
  });