
                <!-- Navigation Starts -->
                <header class="header fixed clearfix">
                    <div class="container">
                        <div class="col-lg-3 col-md-4">
                            <div class="header-left clearfix">
                                <!-- logo -->
                                <a href="/" class="brand_link">
                                    <div class="logo media" title = "<?= __('go home'); ?>">
                                        <?=  HTML::wrap( HTML::image( 'images/brand.png', [ 'alt' => $settings->site_name . ' ' . __( 'logo' ), 'id' => 'logo', 'class' => 'media-object' ] ), [ 'class' => 'pull-xs-left media-left' ] ) .
                                             HTML::wrap( '<span class="media-heading">' . $settings->site_name . '</span>', [ 'class' => 'media-body form-control' ] ); ?>
                                    </div>
                                </a>
                                <!-- name-and-slogan -->
                                <div class="site-slogan">
                                        <?= $settings->site_slogan; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-8">
                            <!-- main-navigation start -->
                            <div class="header-right clearfix">
                                <div class="main-navigation animated">
                                    <nav class="navbar navbar-default">
                                        <div class="container-fluid">
                                            <!-- Toggle get grouped for better mobile display -->
                                            <div class="navbar-header hidden-md-up">
                                                <?= Form::button( 'navbar-toggler', '&#9776;', [ 'type' => 'button', 'class' => 'navbar-toggler', 'data-toggle' => 'collapse', 'data-target' => '#navbar-collapse-1' ]); ?>
                                            </div>
                                            <div class="collapse navbar-toggleable-sm" id="navbar-collapse-1">
                                                <?php if ( $menu ): ?>
                                                <ul class="nav navbar-nav navbar-right">
                                                    <?php foreach ($menu as $name => $item): ?>
                                                    
                                                    <?php if ( strpos($item->cls, 'dropdown') !== FALSE ):?>
                                                    <li class="<?= $item->cls; ?> nav-item">
                                                        <?=HTML::anchor('#', __( $name ), array('data-toggle'=>'dropdown'))?>
                                                        <ul class="dropdown-menu">
                                                            <?php foreach ($item as $i_name => $i_link):?>
                                                            <?php  if(is_object($i_link)): ?>
                                                                <?php if ($i_link->cls == 'separator') $i_name = '';?>
                                                            <li<?php if( $i_link->cls ): ?> class="<?= $i_link->cls; ?>"<?php endif; ?>><?= ( $i_link->href ) ? HTML::anchor( $i_link->href, __( $i_name ), [ 'class' => 'dropdown-item' ] ) : '<h4>' . __( $i_name ) . '</h4>' ?></li>
                                                            <?php  endif; ?>
                                                            
                                                            <?php endforeach; ?>
                                                        </ul>

                                                    <?php else: ?>

                                                    <li<?php if($item->cls):?> class="<?=$item->cls;?> nav-item"<?php endif;?>>
                                                        <?= ($item->cls == 'divider-vertical') ? '' : HTML::anchor($item->href, $name);?>

                                                    <?php endif?>
                                                    </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </nav>
                                </div>
                                
                            </div>
                            <!-- main-navigation end -->
                        </div>
                    </div>
                </header>