<nav class="navbar navbar-full navbar-dark bg-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#admin-menu">
            &#9776;
        </button>
        <div id="admin-menu" class="collapse-sm-up">
            <ul class="nav navbar-nav nav-inline">
                <li class="nav-item"><?= HTML::anchor( 'logout', '<i class="icon-off"></i>', [ 'title' => __( 'Log out' ), 'class' => 'nav-link' ] ); ?></li>
                <li class="nav-item"><?= HTML::anchor('/', '<i class="icon-globe"></i>', [ 'title' => __( 'Go frontend' ), 'class' => 'nav-link' ] ); ?></li>
                <li class="nav-item m-r-3"><?=HTML::anchor( '/blog/wp-admin', 'WP', [ 'title' => __( 'Blog admin area' ), 'class' => 'nav-link' ] );?></li>

                <?php foreach ( $menu as $name => $item ): ?>

                <?php $class = ( in_array( $select, $item ) ) ? 'nav-item active' : 'nav-item'; ?>

                <?php if ( count( $item ) > 1 ): ?>
                <li class="<?= $class; ?> dropdown">
                    <?=HTML::anchor('#', $name.' <b class="caret"></b>', array('class'=>'dropdown-toggle nav-link', 'data-toggle'=>'dropdown'))?>
                    <div class="dropdown-menu">
                        <?php foreach ( $item as $n => $m ): ?>
                        <?php $cls = ( $select == $m ) ? 'dropdown-item active' : 'dropdown-item'; ?>
                        <?= HTML::anchor( sprintf( 'admin/%s', $m ), $n, [ 'class' => $cls ] ); ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                <li class="<?= $class; ?>"><?= HTML::anchor( sprintf( 'admin/%s', $item[ 0 ] ), $name, [ 'class' => 'nav-link' ] ); ?>
                <?php endif; ?>
                </li>

                <?php endforeach; ?>

            </ul>
        </div>
    </div>
</nav>

