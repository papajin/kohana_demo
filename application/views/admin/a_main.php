<?php defined('SYSPATH') or die('No direct script access.');
?>
<div class="row">
    <div class="col-md-8" id="controls">
        <h4><?= __( 'Fast search' ); ?></h4>
        <div id="search_toggle" class="btn-group text-center" data-toggle="buttons">
            <?= Form::label( 'option_pages',
                Form::radio( 'options', NULL, FALSE, [ 'id' => 'option_pages' ] )
                . __( 'Pages' ), [ 'class' => 'btn btn-primary' ] ); ?>
            <?= Form::label( 'option_articles',
                Form::radio( 'options', NULL, FALSE, [ 'id' => 'option_articles' ] )
                . __( 'Articles' ), [ 'class' => 'btn btn-primary' ] ); ?>
        </div>
        <p></p>
        <?=  Form::open('', [ 'id' => 'fast_search', 'class' => 'form-horizontal' ] );?>
        <?//= Form::hidden('search_sourse', $articles, array('id'=>'sourse')); ?>
            <div class="form-group row">
                <?= Form::label( 'id_search', __( 'By id' ), [ 'class' => 'col-sm-4 form-control-label' ] ); ?>
              <div class="col-sm-8">
                <?= Form::input('id_search', '', [ 'class' => 'typeahead form-control', 'id' => 'id_search', 'data-name' => 'id', 'placeholder' => 'id' ] ); ?>
              </div>
            </div>
            <div class="form-group row">
                <?= Form::label( 'alias_search', __( 'By alias' ), [ 'class' => 'col-sm-4 form-control-label' ] ); ?>
              <div class="col-sm-8">
                <?= Form::input('alias_search', '', [ 'class' => 'typeahead form-control', 'id' => 'alias_search', 'data-name' => 'alias', 'placeholder' => 'alias', 'autocomplete' => 'off' ] ); ?>
              </div>
            </div>
            <div class="form-group row">
                <?= Form::label( 'title_search', __( 'By name' ), [ 'class' => 'col-sm-4 form-control-label' ] ); ?>
              <div class="col-sm-8">
                <?= Form::input('title_search', '', [ 'class' => 'typeahead form-control', 'id' => 'title_search', 'data-name' => 'title', 'placeholder' => __( 'name' ), 'autocomplete' => 'off' ] ); ?>
              </div>
            </div>
            <?= Form::hidden( 'pages_source', $pages, [ 'id' => 'pages_source' ] ); ?>
            <?= Form::hidden( 'articles_source', $articles, [ 'id' => 'articles_source' ] ); ?>
        <?=Form::close();?>
    </div>
    <div class="col-md-4 btn-group-vertical">
        <div class="btn-group delete_cache">
            <?= Form::button( 'delete_cache', sprintf( '<i class="icon-arrows-cw"></i> %s', __( 'Clear cache' ) ), [ 'class' => 'btn btn-secondary dropdown-toggle', 'data-toggle' => 'dropdown', 'aria-haspopup' => 'true', 'aria-expanded' => 'false' ] ); ?>
            <div class="dropdown-menu">
                <?= HTML::anchor( '#all', __( 'all' ), [ 'class' => 'dropdown-item' ] ); ?>
                <?= HTML::anchor( '#pages', __( 'of pages' ), [ 'class' => 'dropdown-item' ] ); ?>
                <?= HTML::anchor( '#widgets', __( 'of widgets' ), [ 'class' => 'dropdown-item' ] ); ?>
                <?= HTML::anchor( '#blog_widgets', __( 'of blog widgets' ), [ 'class' => 'dropdown-item' ] ); ?>
            </div>
        </div>
        <?= Form::button( 'generate_map', __( 'Regenerate XML sitemap' ), [ 'id' => 'generate_map', 'class' => 'btn btn-secondary' ] ); ?>
    </div>
</div>