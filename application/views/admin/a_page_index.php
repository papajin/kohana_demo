<?php defined('SYSPATH') or die('No direct script access.');

    $table = '<table class="table table-striped table-bordered table-sm table-hover">';
?>
<div class="row m-t-1">
    <?= $table; ?>
        <thead>
            <tr>
                <th><?= __( 'ID' ); ?></th>
                <th><?= __( 'name' ); ?></th>
                <th><?= __( 'alias' ); ?></th>
                <th class="text-center"><?= __( 'functions' ); ?></th>
            </tr>
        </thead>
        <tbody class="sortable">

    <?php $cnt = count( $pages );?>
    <?php for ( $i = 0; $i < $cnt; $i++ ): ?>
    <tr data-id="<?= $pages[ $i ]->id; ?>">
        <td width="40"><?= $pages[ $i ]->id; ?></td>
        <td><?=HTML::anchor( 'admin/page/edit/'. $pages[ $i ]->id, $pages[ $i ]->title, [ 'class' => 'btn btn-link btn-xs' ] ); ?></td>
        <td width="250"><?= $pages[ $i ]->alias; ?></td>
        <td width="145">
            <div class="btn-group btn-group-sm">
                <?=HTML::anchor( $pages[ $i ]->make_uri(), '<i class="icon-search"></i>', [ 'target' => '_blank', 'title' => __( 'view' ), 'class' => 'btn btn-secondary' ] ); ?>
                <?=HTML::anchor( 'admin/page/edit/' . $pages[ $i ]->id, '<i class="icon-pencil"></i>', [ 'title' => __( 'edit' ), 'class' => 'btn btn-secondary' ] ); ?>
                <?=HTML::anchor( '#', '<i class="icon-arrows-cw"></i> ', [ 'title' => __( 'Clear cache' ), 'class' => 'btn btn-secondary delete_cache' ] ); ?>
            </div>
        </td>
    </tr>

    <?php if ( $i < $cnt-1 AND $pages[ $i ]->parent_id != $pages[ $i+1 ]->parent_id ): ?>
    </tbody></table>
    <hr />
    <?= $table; ?>
    <tbody class="sortable">
    <?php endif; ?>
    <?php endfor; ?>
    </tbody>
    </table>
</div>