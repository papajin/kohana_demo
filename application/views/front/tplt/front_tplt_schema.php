<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Subtemplate for astro chart.
 */
?>
<div>
    <?= HTML::anchor( '/schema_flash.html', __( 'Switch to flash' ), [ 'title'=> __( 'If your browser does not support HTML5, try this' ), 'class' => 'link' ] ); ?>
</div>
<div id="btn-toolbar" class="row" role="toolbar">
    <div class="col-lg-6">
        <div class="pull-xs-left">
          <?= Form::button( 'elem_toggle', __( 'Elements' ), [ 'class' => 'btn btn-secondary btn-sm', 'data-toggle' => 'button', 'data-target' => 'elem_', ] ); ?>
          <ul class="nav">
          <?php foreach( __( 'elem_list' ) as $i => $el ): ?>
            <li><?= Form::label( 'elem_' . $i,
                             sprintf( '%s<span class="c-indicator"></span> %s',
                                    Form::checkbox( 'elem_', $i, TRUE, [ 'id' => 'elem_' . $i ] ),
                                    $el ),
                             [ 'class' => 'c-input c-checkbox' ] ); ?></li>
          <?php endforeach; ?>
          </ul>
        </div>
        <div class="pull-xs-left">
            <?= Form::button( 'aspects_toggle', __( 'Aspects' ), [ 'class' => 'btn btn-secondary btn-sm', 'data-toggle' => 'button', 'data-target' => 'aspect', ] ); ?>
            <ul class="nav">
                <li><?= Form::label( 'major',
                    sprintf( '%s<span class="c-indicator"></span> %s',
                        Form::checkbox( 'aspect', 'major', TRUE, [ 'id' => 'major' ] ),
                        __( 'major' ) ),
                    [ 'class' => 'c-input c-checkbox' ] ); ?></li>
                <li><?= Form::label( 'minor',
                    sprintf( '%s<span class="c-indicator"></span> %s',
                        Form::checkbox( 'aspect', 'minor', TRUE, [ 'id' => 'minor' ] ),
                        __( 'minor' ) ),
                    [ 'class' => 'c-input c-checkbox' ] ); ?></li>
            </ul>
        </div>
        <?= Form::button( 'signs_toggle', __( 'Signs' ), [ 'class' => 'btn btn-secondary btn-sm pull-xs-left', 'data-toggle' => 'button', 'id' => 'sign', ] ); ?>
        <?= Form::button( 'angles_toggle', __( 'Angles' ), [ 'class' => 'btn btn-secondary btn-sm pull-xs-left', 'data-toggle' => 'button', 'id' => 'degree_' ] ); ?>
    </div>
    <div class="col-lg-6" id="togglers">
        <div class="col-xs-6 col-md-4 btn-group-vertical">
            <h4><?= __( 'Crosses' ); ?></h4>
            <a href="[0,3,6,9]" data-clicks="" class="btn btn-secondary btn-sm m-t-0"><?= __( 'Pivotal' ); ?></a>
            <a href="[1,4,7,10]" data-clicks="" class="btn btn-secondary btn-sm m-t-0"><?= __( 'Fixed' ); ?></a>
            <a href="[2,5,8,11]" data-clicks="" class="btn btn-secondary btn-sm m-t-0"><?= __( 'Inflexive' ); ?></a>
        </div>
        <div class="col-xs-6 col-md-4 btn-group-vertical">
            <h4><?= __( 'Quadrants' ); ?></h4>
            <a href="[0,1,2]" data-clicks="" class="btn btn-secondary btn-sm m-t-0">I (<?= __( 'Spring' ); ?>)</a>
            <a href="[3,4,5]" data-clicks="" class="btn btn-secondary btn-sm m-t-0">II (<?=  __( 'Summer' ); ?>)</a>
            <a href="[6,7,8]" data-clicks="" class="btn btn-secondary btn-sm m-t-0">III (<?=  __( 'Fall' ); ?>)</a>
            <a href="[9,10,11]" data-clicks="" class="btn btn-secondary btn-sm m-t-0">IV (<?=  __( 'Winter' ); ?>)</a>
        </div>
        <div class="col-xs-6 col-md-4 btn-group-vertical">
            <h4><?= __( 'Semispheres' ); ?></h4>
            <a href="[6,7,8,9,10,11]" data-clicks="" class="btn btn-secondary btn-sm m-t-0"><?= __( 'Upper' ); ?></a>
            <a href="[0,1,2,3,4,5]" data-clicks="" class="btn btn-secondary btn-sm m-t-0"><?= __( 'Lower' ); ?></a>
            <a href="[0,1,2,9,10,11]" data-clicks="" class="btn btn-secondary btn-sm m-t-0"><?= __( 'Eastern' ); ?></a>
            <a href="[3,4,5,6,7,8]" data-clicks="" class="btn btn-secondary btn-sm m-t-0"><?= __( 'Western' ); ?></a>
        </div>
    </div>
</div><!-- /.btn-toolbar -->
<div id="schema" class="chart"></div>