
            <!-- Top Header Starts -->
            <div class="header-top">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-2 col-sm-6">
                            <div class="header-top-first clearfix">
                                <ul class="social-links clearfix hidden-xs-down">
                                    <li class="googleplus">
                                        <?= HTML::anchor( 'https://plus.google.com/u/0/112477047573867097337/posts',
                                            '<i class="icon-gplus"></i>',
                                            [ 'rel' => 'nofollow', 'title' => __( 'My G+ profile' ), 'data-toggle' => 'tooltip', 'data-placement' => 'bottom' ] ); ?>
                                    </li>
                                    <li class="facebook">
                                        <?= HTML::anchor( 'https://www.facebook.com/ovivanetz/',
                                            '<i class="icon-facebook"></i>',
                                            [ 'rel' => 'nofollow', 'title' => __( 'My Facebook profile' ), 'data-toggle' => 'tooltip', 'data-placement' => 'bottom' ] ); ?>
                                    </li>
                                </ul>
                                <div class="social-links hidden-sm-up">
                                    <div class="btn-group dropdown">
                                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class="icon-share"></i></button>
                                        <ul class="dropdown-menu dropdown-animation">
                                            <li class="googleplus"><a rel="nofollow" href="https://plus.google.com/u/0/112477047573867097337/posts"><i class="icon-gplus"></i></a></li>
                                            <li class="facebook"><a rel="nofollow" target="_blank" href="https://www.facebook.com/ovivanetz/"><i class="icon-facebook"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-10 col-sm-6">

                            <div id="header-top-second"  class="clearfix">

                                <!-- header top dropdowns start -->
                                <!-- ================ -->
                                <div class="header-top-dropdown"> 
                                    <!-- Search -->
                                    <div class="btn-group dropdown">
                                        <?= Form::button('', '<i class="icon-search"></i> ' . __( 'Search' ), [ 'class' => "btn", 'data-toggle' => "dropdown" ]); ?>
                                        <ul class="dropdown-menu dropdown-menu-right dropdown-animation">
                                            <li>
                                                <?php
                                                    echo Form::open( '/search.html', [ 'id' => "cse-search-box", 'method' => 'get', 'role' => "search", 'class' => "search-box" ] );
                                                    echo HTML::wrap(
                                                            Form::label( 'q', '<i class="icon-search"></i>', [ 'class' => 'input-group-addon' ] ) .
                                                            Form::input( 'q', '', [ 'class' => "form-control", 'placeholder' => __( 'Search' ) ] ),
                                                            [ 'class' => "input-group" ]
                                                        );
                                                    echo Form::close(); 
                                                ?>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /Search -->
                                    <!-- FB subscription -->
                                    <div class="btn-group dropdown">
                                        <?= Form::button('', '<i class="icon-newspaper"></i> ' . __( 'News on email' ), [ 'class' => "btn", 'data-toggle' => "dropdown" ]); ?>
                                        <ul class="dropdown-menu dropdown-menu-right dropdown-animation">
                                            <li>
                                                <?php
                                                    echo Form::open( 'https://feedburner.google.com/fb/a/mailverify?uri=ivanets&amp;loc=ru_RU',
                                                            [   'accept-charset' => 'utf-8', 
                                                                'target' => "popupwindow", 'class' => "login-form",
                                                                'onsubmit' => "window.open('http://feedburner.google.com/fb/a/mailverify?uri=ivanets', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true"
                                                            ] );
                                                    echo Form::hidden('url', 'ivanets');
                                                    echo Form::hidden('loc', 'ru_RU');
                                                
                                                    echo HTML::wrap(
                                                            Form::label( 'feed_email', '&#64;', [ 'class' => 'input-group-addon', 'title' => __('Enter your email for news'), 'data-toggle' => 'tooltip', 'data-placement' => "bottom" ] ) .
                                                            Form::input( 'feed_email', '', [ 'type' => 'email', 'id' => 'feed_email', 'class' => "form-control", 'placeholder' => __( 'Your email' ) ] ),
                                                            [ 'class' => "input-group" ]
                                                        );
                                                    echo Form::button( 'FBsubmit', 'OK', [ 'type' => 'submit', 'class' => 'btn btn-default btn-sm' ] );
                                                    echo Form::close(); ?>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="btn-group dropdown">
                                        <?= Form::open('', [ 'method' => 'get' ] ); ?>
                                        <?= Form::button('lang', '<i class="icon-language"></i> ' . __( 'switch_language' ), [ 'class' => "btn", 'type' => 'submit', 'value' => $new_lang ] ); ?>
                                        <?= Form::close(); ?>
                                    </div>
                                </div>
                                    <!--  header top dropdowns end -->
                            </div>
                                <!-- header-top-second end -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Header ends -->