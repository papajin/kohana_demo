/**
 * Check correct form data before submit.
 * Required fields check supposed to be done by browser.
 * We are going to check Name length and spam protection only.
 */
$( '#addcom' ).submit(
    function() {
        var $f = $(this), res = true;
        $f.find('.form-group').removeClass('has-error');
        
        if ( $f.find('#name').val().length < 3 ) {
            res = false;
            $f.find('#name').parent('.form-group').addClass('has-error');
        }
        
        if ( $f.find( 'option:checked').val() !== $f.find( ':hidden[name=answer]' ).val() ) {
            res = false;
            $f.find('#is_spam').parents('.form-group').addClass('has-error');
        }

        if ( ! res ) $f.find('.has-error .form-control').eq(0).focus();

        return res;
    }
);