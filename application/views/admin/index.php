<!DOCTYPE HTML>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title><?= $page_title; ?></title>
    <meta name="robots" content="noindex, nofollow" />
    <!-- Styles -->
    <?php
    // Get styles if any
    foreach ( $styles as $style )
        ! property_exists( $style_list, $style ) OR print HTML::style( $style_list->$style ) . EOL_HT;
    ?>
</head>
<body>
    
    <?= $menu_admin; ?>
    <div class="container m-t-3 p-t-2">
        <h2><?= $page_title; ?></h2>
    </div>
    <div class="container m-t-3">
    <!-- Content block -->
        <?php foreach ( $content as $block ): ?>
            <?= $block; ?>
        <?php endforeach; ?>
    <!-- /Content block -->
    </div>

    <!-- scripts -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="\/media\/js\/jquery.min.js"><\/script>')</script>

    <?php  // Get scripts
    foreach ( $scripts as $script )
        ! property_exists( $script_list, $script ) OR print HTML::script( $script_list->$script ) . EOL_HT;
    ?>
    <?php if ( ! empty( $alerts ) ): ?>
        <!-- alerts -->
        <?= Form::hidden( 'alerts', $alerts, [ 'id' => 'alerts' ] ); ?>
        <!-- /alerts -->
    <?php endif; ?>
</body>
</html>