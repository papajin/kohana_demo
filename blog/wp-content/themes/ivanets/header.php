<?php
/**
 * @package WordPress
 * @subpackage Ivanets_Theme
 */

// WP uses some files of the parent site, hence base url is level up.
$base_url = substr( get_site_url(), 0, strpos( get_site_url(), 'blog' ) );

// Styles
//wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css' );
wp_enqueue_style( 'fontello', $base_url . 'media/css/fontello.css' );
wp_enqueue_style( 'bootstrap', $base_url . 'media/css/bootstrap.min.css' );
wp_enqueue_style( 'tether', $base_url . 'media/css/tether.min.css' );
wp_enqueue_style( 'style', $base_url . 'media/css/style.css', [], '1.4' );
wp_enqueue_style( 'light_green', $base_url . 'media/css/light_green.css', [], '1.3' );
wp_enqueue_style( 'animate', $base_url . 'media/css/animate.min.css' );
wp_enqueue_style( 'blog_style', esc_url( get_template_directory_uri() ) . '/style.css' );

// Scripts
wp_enqueue_script( 'jquery2.2.2', '//ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js', [], NULL, TRUE );
wp_enqueue_script( 'tether', $base_url . 'media/js/tether.min.js', [], NULL, TRUE );
wp_enqueue_script( 'bootstrap', $base_url . 'media/js/bootstrap.min.js', [ 'tether', 'jquery2.2.2' ], NULL, TRUE );
wp_enqueue_script( 'modernizr', '//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js', [], NULL, TRUE );
wp_enqueue_script( 'modal', $base_url . 'media/js/modal.js', [], '0.1.0', TRUE );
wp_enqueue_script( 'front', $base_url . 'media/js/front.js', [ 'jquery2.2.2' ], '1.3', TRUE );
?>
<!--[if IE 10]> <html lang="<?= WPLG; ?>" class="ie10"> <![endif]-->
<!--[if IE 9]> <html lang="<?= WPLG; ?>" class="ie9"> <![endif]-->
<!--[if IE 8]> <html lang="<?= WPLG; ?>" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<!DOCTYPE html>
<!--<![endif]-->
<html lang="<?= WPLG; ?>">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php wp_title('|', true, 'right'); ?> <?php bloginfo('name'); ?></title>

<?php wp_head(); ?>
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
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />


<?php include_once KOHANA_APP . 'views/front/misc/front_misc_analyticstracking.php' ;?>
</head>
<body class="no-trans">
    <!-- scrollToTop -->
    <div class="scrollToTop"><i class="icon-up-big fa-3"></i></div>
    <div class="page-wrapper">
        <?php ivanetsMenu::echo_widget( 'topper' ); ?>
        <?php ivanetsMenu::echo_widget( 'header' ); ?>
        <!-- page-intro start-->
        <div class="page-intro">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <?php the_breadcrumb(); ?>
                    </div>
                </div>
            </div>
        </div>
        <section class="main-container">
            <div class="container">
                <div class="row">