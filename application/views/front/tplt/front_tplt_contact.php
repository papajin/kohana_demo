<?php
    $show_success = ( !! Arr::get( $data, 'submit' ) AND empty( $errors ) );
    $show_errors = ( !! Arr::get( $data, 'submit' ) AND ! empty( $errors ) );
?>
                <!-- main-container start -->
                <!-- ================ -->
                <section class="main-container">

                    <div class="container">
                        <div class="row">

                            <!-- main start -->
                            <!-- ================ -->
                            <div class="main col-md-8" role="main">

                                <!-- page-title start -->
                                <!-- ================ -->
                                <h1 class="page-title"><?= __( 'Contact us' ); ?></h1>
                                <!-- page-title end -->
                                <?= $page->content; ?>
                                <div class="alert alert-success<?php print ( $show_success ) ? '' : ' collapse'; ?>" id="MessageSent">
                                        <?= __( 'We have received your message, we will contact you very soon.' ); ?>
                                </div>
                                <div class="alert alert-danger<?php print ( $show_errors ) ? '' : ' collapse'; ?>" id="MessageNotSent">
                                    <?php if ( $show_errors ): ?>
                                    <ul>
                                        <?php foreach ( $errors as $error ): ?>
                                            <li><?= $error; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <?php endif; ?>
                                </div>
                                <div class="contact-form">
                                    <?= Form::open( NULL, [ 'method' => 'post', 'id' => 'addcom', 'role'=>'form' ] ); ?>
                                        <div class="form-group">
                                            <?= Form::label( 'name', __( 'Your name' ) . '*' ); ?>
                                            <?= Form::input( 'name', Arr::get( $data, 'name' ), [ 'class' => 'form-control', 'id' => 'name', 'placeholder' => '', 'tabindex' => 1, 'required' => 1 ] ); ?>
                                        </div>
                                        <div class="form-group">
                                            <?= Form::label( 'email', __( 'Your email' ) . '*' ); ?>
                                            <?= Form::input( 'email', Arr::get( $data, 'email' ), [ 'class' => 'form-control', 'id' => 'email', 'type' => 'email', 'placeholder' => '', 'tabindex' => 2, 'required' => 1 ] ); ?>
                                        </div>
                                        <div class="form-group">
                                            <?= Form::label( 'comtext', __( 'Your message' ) . '*' ); ?>
                                            <?= Form::textarea( 'comtext', Arr::get( $data, 'comtext'), [ 'class' => 'form-control', 'id' => 'comtext', 'rows' => 3, 'tabindex' => 3, 'required' => 1 ] ); ?>
                                        </div>
                                        <div class="form-group row">
                                            <?= Form::label( 'is_spam', __( 'Spam check' ) . '*', [ 'class' => 'col-xs-5 col-sm-4' ] ); ?>
                                            <div class="col-xs-7 col-sm-8">
                                                <?= Form::select( 'is_spam', Arr::get( $is_spam, 'radios'), NULL, [ 'class' => 'form-control', 'id' => 'is_spam', 'tabindex' => 4, 'required' => 1 ] ); ?>
                                             </div>
                                            <?= Form::hidden( 'answer', Arr::get( $is_spam, 'answer' ) ); ?>
                                        </div>
                                        <?= Form::submit('submit', __( 'Submit' ), [ 'class' => 'submit-button btn btn-default', 'tabindex' => 5 ] ); ?>
                                    <?= Form::close(); ?>
                                </div>
                            </div>
                            <!-- main end -->

                            <!-- sidebar start -->
                            <aside class="col-md-4">
                                    <div class="sidebar">
                                            <div class="side vertical-divider-left">
                                                <?php if ( ! empty( $sidebar ) ): ?>
                                                    <?php foreach ( $sidebar as $widget ): ?>
                                                        <?= $widget; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                    </div>
                            </aside>
                            <!-- sidebar end -->

                        </div>
                    </div>
                </section>
                <!-- main-container end -->