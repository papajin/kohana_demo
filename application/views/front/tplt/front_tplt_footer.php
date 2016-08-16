				<div class="footer">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="footer-content">
                                    <?php include_once( Kohana::find_file( 'views/front/misc', 'front_misc_fb' ) );?>
                                </div>
                            </div>
                            <div class="space-bottom hidden-lg hidden-xs"></div>
                            <div class="col-sm-6 col-md-2">
                                <div class="footer-content">
                                    <h2><?= __( 'Links' ); ?></h2>
                                        <nav>
                                            <ul class="nav nav-pills nav-stacked">
                                                <?php foreach ( $footer_menu as $anchor => $href ): ?>
                                                <li class="nav-item"><?= HTML::anchor($href, __( $anchor ), [ 'class' => 'nav-link' ] ); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </nav>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 col-md-offset-1">
                                <div class="footer-content">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="space-bottom hidden-lg hidden-xs"></div>
                    </div>
				</div>
				<!-- .footer end -->