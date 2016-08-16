<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Action call button (inquiry form page).
 */
?>
<div class="text-center">
    <?= HTML::anchor( Route::get( 'articles' )->uri( [ 'id' => 'anketa' ] ), __( 'Send enquiry' ), [ 'class' => 'btn btn-warning' ] ); ?>
</div>