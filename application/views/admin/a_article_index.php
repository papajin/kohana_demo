<?php defined('SYSPATH') or die('No direct script access.'); ?>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <?= Form::select( 'category_id', $options, $category_id, [ 'class' => 'form-control' ] ); ?>
    </div>
</div><br />
    
<div class="row">
    <table class="table table-striped table-bordered table-sm table-hover">
        <thead>
            <tr>
                <th><?= __( 'ID' ); ?></th>
                <th><?= __( 'name' ); ?></th>
                <th><?= __( 'alias' ); ?></th>
                <th class="text-center"><?= __( 'functions' ); ?></th>
            </tr>
        </thead>
        <tbody class="sortable">
            <?php for ( $i = 0; $i < count( $articles ); $i++ ): ?>
            <?php $tags = $articles[ $i ]->terms->find_all()->as_array( 'term_id', 'name' ); ?>
            <tr data-id="<?= $articles[ $i ]->id; ?>">
                <td width="40"><?= $articles[ $i ]->id; ?></td>
                <td>
                    <?= HTML::anchor( 'admin/article/edit/'. $articles[ $i ]->id, $articles[ $i ]->title ); ?>
                    <?php if( count( $tags ) ): ?>
                    <i data-toggle="tooltip" title="<?= implode( ', ', $tags ); ?>" class="icon-tags pull-xs-right"></i>
                    <?php endif; ?>
                    <?php if( $articles[ $i ]->has_children() ): ?>
                    <?= Form::button( 'children_'.$articles[ $i ]->id, '<i class="icon-menu"></i>', [ 'class' => 'btn btn-secondary btn-sm', 'data-data' => json_encode( $articles[ $i ]->get_children() ) ] ); ?>
                    <?php endif; ?>
                </td>
                <td width="250"><?= $articles[ $i ]->alias; ?></td>
                <td width="145">
                    <div class="btn-group btn-group-sm">
                        <?=HTML::anchor( $articles[$i]->make_uri(), '<i class="icon-search"></i>', [ 'target' => '_blank', 'title' => __( 'view' ), 'class' => 'btn btn-secondary' ] ); ?>
                        <?=HTML::anchor( 'admin/article/edit/' . $articles[ $i ]->id, '<i class="icon-pencil"></i>', [ 'title' => __( 'edit' ), 'class' => 'btn btn-secondary'] ); ?>
                        <?=HTML::anchor('#', '<i class="icon-arrows-cw"></i> ', [ 'title' => __( 'Clear cache' ), 'class' => 'btn btn-secondary delete_cache' ] ); ?>
                    </div>
                </td>
            </tr>
            <?php endfor; ?>

        </tbody>
    </table>
</div>