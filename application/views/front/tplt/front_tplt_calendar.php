<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Calendar template.
 */
    function month_year ( $str, $months ) {
        $dt = explode('_', $str );
        return sprintf( '%s %s', $months[ ( int ) $dt[ 1 ] ], $dt[ 0 ] );
    }
    $i = 0;
?>
        <h1 class="title"><?= __( $page->title ); ?></h1>
        <div class="separator-2"></div>
        <div class="alert alert-warning">
            <?= __( 'EET/EEST (Kyiv local time) used in the calendar! The difference with GMT is +2 hours (+3 hours for DST).' ); ?>
        </div>
        <div class="m-y-2">
            <h4 class="text-xs-center"><?= sprintf( '%s %d', $months[ ( int ) $m ], $y ); ?></h4>
            <div class="overlay-container">
                <?php for ( ; $i < $actual; $i++ ): ?>
                <?= HTML::anchor( sprintf( '/images/calendar/%s.png', $calendars[ $i ] ), '', [ 'class' => 'invisible', 'title' => month_year( $calendars[ $i ], $months ) ] ); ?>
                <?php endfor; ?>
                <?= Form::hidden( 'months', json_encode( $months ), [ 'id' => 'months' ] ); ?>
                <?= HTML::image( sprintf( '/images/calendar/%s.png', $calendars[ $i ] ),
                        [ 'alt' => __( 'astrological calendar'), 'id' => 'calendar', 'class' => 'img-fluid' ] ); ?>
                <?= HTML::anchor( sprintf( '/images/calendar/%s.png', $calendars[ $i ] ),
                                '<i class="icon-zoom-in"></i>',
                                [ 'class' => 'overlay large image-link', 'title' => month_year( $calendars[ $i++ ], $months ) ] ); ?>
                <?php for ( ; $i < count ( $calendars ); $i++ ): ?>
                <?= HTML::anchor( sprintf( '/images/calendar/%s.png', $calendars[ $i ] ), '', [ 'class' => 'invisible', 'title' => month_year( $calendars[ $i ], $months ) ] ); ?>
                <?php endfor; ?>
            </div>
            <div id="explanation" class="blogpost m-y-2 p-a-1">
                <h3><?= __( 'Explanation of the lunar calendar' ); ?></h3>
                <hr>
                <div class="media">
                    <div class="media-left font-weight-bold"><div class="media-object"><?= __( 'Planets' ); ?>:</div></div>
                    <div class="media-body">
                        <ul class="nav nav-inline">
                            <?php foreach ( __( 'planets_list' ) as $c => $n ): ?>
                            <?= HTML::wrap( sprintf( '<i class="alma-%s"></i>- %s', strtolower( $c ), $n ), [ 'class' => 'nav-item' ], 'li' ); ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="media">
                    <div class="media-left font-weight-bold"><div class="media-object"><?= __( 'Retrograde motion' ); ?>:</div></div>
                    <div class="media-body">
                        <ul class="nav nav-inline">
                            <?= HTML::wrap( sprintf( '<i class="alma-go-dir"></i>- %s', __( 'Direct motion' ) ), [ 'class' => 'nav-item' ], 'li' ); ?>
                            <?= HTML::wrap( sprintf( '<i class="alma-go-retro"></i>- %s', __( 'Retrograde motion' ) ), [ 'class' => 'nav-item' ], 'li' ); ?>
                        </ul>
                    </div>
                </div>
                <div class="media">
                    <div class="media-left font-weight-bold"><div class="media-object"><?= __( 'Signs' ); ?>:</div></div>
                    <div class="media-body">
                        <ul class="nav nav-inline">
                            <?php foreach ( __( 'signs_list' ) as $c => $n ): ?>
                                <?= HTML::wrap( sprintf( '<i class="alma-%s"></i>- %s', strtolower( $c ), $n ), [ 'class' => 'nav-item' ], 'li' ); ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="media">
                    <div class="media-left text-nowrap font-weight-bold"><div class="media-object"><?= __( 'Lunar nodes' ); ?>:</div></div>
                    <div class="media-body">
                        <ul class="nav nav-inline">
                            <?= HTML::wrap( sprintf( '<i class="alma-asc-lunar-node"></i>- %s', __( 'Ascending lunar node' ) ), [ 'class' => 'nav-item' ], 'li' ); ?>
                            <?= HTML::wrap( sprintf( '<i class="alma-desc-lunar-node"></i>- %s', __( 'Descending lunar node' ) ), [ 'class' => 'nav-item' ], 'li' ); ?>
                        </ul>
                    </div>
                </div>
                <div class="media">
                    <div class="media-left text-nowrap font-weight-bold"><div class="media-object"><?= __( 'Solar and lunar cycle' ); ?>:</div></div>
                    <div class="media-body">
                        <ul class="nav nav-inline">
                            <?= HTML::wrap( sprintf( '<i class="alma-2-moon text-info"></i>- %s', __( 'Phase of solar and lunar cycle' ) ), [ 'class' => 'nav-item' ], 'li' ); ?>
                            <?= HTML::wrap( sprintf( '<i class="alma-go-3-moon text-info"></i>- %s', __( 'Change of solar and lunar cycle phase' ) ), [ 'class' => 'nav-item' ], 'li' ); ?>
                        </ul>
                    </div>
                </div>
                <div class="media">
                    <div class="media-left font-weight-bold"><div class="media-object"><?= __( 'Aspects' ); ?>:</div></div>
                    <div class="media-body">
                        <ul class="nav nav-inline">
                            <?php foreach ( __( 'aspects_list' ) as $c => $n ): ?>
                                <?= HTML::wrap( sprintf( '<i class="alma-%s"></i>- %s', strtolower( $c ), $n ), [ 'class' => sprintf( 'nav-item %s', ( ( strtolower( $c ) == 'trigon' OR strtolower( $c ) == 'sextile' ) ? 'text-success' : 'text-danger' ) ) ], 'li' ); ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="">
            <?= $page->content; ?>
        </div>
