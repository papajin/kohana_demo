<!DOCTYPE html>
<html>
<head>
    <title><?= $settings->site_name; ?> | <?= $page_title; ?></title>
    <meta name="robots" content="noindex, nofollow" />
    <meta content="text/html; charset=utf8" http-equiv="content-type">
    <!-- Styles -->
    <?php
    foreach ( $styles as $style )
        ! property_exists( $style_list, $style ) OR print HTML::style( $style_list->$style ) . EOL_HT;
    ?>
</head>

<body>
    <section class="main-container">
        <div class="container">
            <div class="row">
                <div class="main">
                    <div class="form-block center-block">
                        <h2 class="title"><?=$page_title?></h2>
                        <hr>
                        <?= Form::open( sprintf( 'login/?uri=%s', $uri ), [ 'class' => 'form-horizontal', 'role' => 'form' ] ); ?>
                            <div class="form-group row">
                                <?= Form::label( 'username', __( 'Login' ), [ 'class' => 'col-sm-3 control-label' ] ); ?>
                                <div class="col-sm-8">
                                    <?=Form::input( 'username', $data[ 'username' ], [ 'id' => 'username', 'class' => 'form-control' ] ); ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <?= Form::label( 'password', __( 'password' ), [ 'class' => 'col-sm-3 control-label' ] ); ?>
                                <div class="col-sm-8">
                                    <?=Form::password( 'password', $data[ 'password' ], [ 'id' => 'password', 'class' => 'form-control' ] ); ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-offset-3 col-sm-8">
                                    <div class="checkbox">
                                        <?= Form::label( 'remember', sprintf( '%s %s', Form::checkbox( 'remember', NULL, ( bool) $data[ 'remember' ], [ 'id' => 'remember' ] ), __( 'Remember me' ) ) ); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-offset-3 col-sm-8">
                                    <?=Form::submit( 'submit', __( 'Sign in' ), [ 'class' => 'btn btn-group btn-default btn-sm' ] ); ?>
                                </div>
                            </div>
                        <?= Form::close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>