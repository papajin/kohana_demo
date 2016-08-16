<?php defined('SYSPATH') or die('No direct script access.');

/*
 * Main template for front end.
 */

?>
<!--[if IE 10]> <html lang="<?= $lang; ?>" class="ie10"> <![endif]-->
<!--[if IE 9]> <html lang="<?= $lang; ?>" class="ie9"> <![endif]-->
<!--[if IE 8]> <html lang="<?= $lang; ?>" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<!DOCTYPE html>
<!--<![endif]-->
<html lang="<?= $lang; ?>">
    <head>
        <meta charset="utf-8">
        <!-- Title here -->
        <? if( $page ): ?>
        <title><?= $page->title; ?></title>
        <!-- Description, Keywords and Author -->
        <meta name="description" content="<?= $page->description; ?>">
        <meta name="keywords" content="<?= $page->keywords; ?>">

        <!-- Open Graph -->
        <meta property="og:url"           content="<?= $url; ?>" />
        <meta property="og:type"          content="website" />
        <meta property="og:title"         content="<?= $page->title; ?>" />
        <meta property="og:description"   content="<?= $page->description; ?>" />
        <meta property="og:site_name"     content="<?= sprintf( '%s, %s', $settings->site_name, $settings->site_slogan ); ?>" />

        <? endif; ?>
        <meta name="author" content="<?= $settings->author; ?>">
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Styles -->
        <?php 
            // Get styles if any
            foreach ( $styles as $style )
                ! property_exists( $style_list, $style ) OR print HTML::style( $style_list->$style ) . EOL_HT;
        ?>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
        <link rel="icon" type="image/png" href="/favicon-196x196.png" sizes="196x196">
        <link rel="icon" type="image/png" href="/favicon-160x160.png" sizes="160x160">
        <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
        <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
        <meta name="msapplication-TileColor" content="#f6da22">
        <meta name="msapplication-TileImage" content="/mstile-144x144.png">
        <?php include_once ( Kohana::find_file( 'views/front/misc', 'front_misc_analyticstracking' ) ) ;?>
    </head>
    
    <body class="no-trans">
        <!-- scrollToTop -->
        <!-- ================ -->
        <div class="scrollToTop"><i class="icon-up-big"></i></div>
        <!--[if lt IE 9]>
        <div class="alert alert-danger"><?= __( 'Your browser supported no longer. Please, upgrade.' ); ?></div>
        <![endif]-->
        <div class="page-wrapper">
        <?php foreach( $content as $block ) echo $block; ?>
        </div>
        <!-- page-wrapper end -->
        
        <!-- scripts -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="\/media\/js\/jquery.min.js"><\/script>')</script>

        <?php  // Get scripts
            foreach ( $scripts as $script )
                ! property_exists( $script_list, $script ) OR print HTML::script( $script_list->$script ) . EOL_HT;
        ?>
        
    </body>
</html>