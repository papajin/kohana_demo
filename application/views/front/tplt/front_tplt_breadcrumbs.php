<!-- breadcrumbs -->
<!-- ================ -->
<div class="page-intro">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <?php if ( $show_home ): ?>
                        <li><i class="icon-home pr-10"></i><a href="/"><?=__( 'Home' ); ?></a></li>
                    <?php endif; ?>
                    <?php foreach ( $inner as $title => $href ): ?>
                    <li><a href="<?= $href; ?>"><?= __( $title ); ?></a></li>
                    <?php endforeach; ?>
                    <?php if ( $active_title ): ?>
                    <li class="active"><?= __( $active_title ); ?></li>
                    <?php endif; ?>
                </ol>
            </div>
        </div>
    </div>
</div>