<?php defined('SYSPATH') or die('No direct script access.'); ?>
<div class="row">
    <div class="btn-group pull-xs-right">
        <?=HTML::anchor('/admin/article/add', '<i class="icon-plus-squared-alt"></i> ' . __( 'new article' ), [ 'class' => 'btn btn-secondary' ] ); ?>
        <?=Form::button( 'save', '<i class="icon-floppy"></i> ' . __( 'save' ),  [ 'class' => 'btn btn-success' ] ); ?>
    </div>
</div>
