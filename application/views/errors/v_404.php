<section class="main-container">
    <div class="container">
        <div class="row">
            <div class="main col-md-6 col-md-offset-3">
                <h1 class="title"><?= __( 'Page not found' ); ?> - 404</h1>
                <br>
                <p><?= __( 'The requested URL :uri was not found on this server.', [ ':uri' => Request::$initial->url( TRUE ) ] ); ?></p>
                <p><?= sprintf( '%s | %s', HTML::anchor( '/', __( 'go home' ) ), HTML::anchor( '/map.html', __( 'Site map' ) ) ); ?></p>
                <?php
                    echo Form::open( '/search.html', [ 'id' => "cse-search-box", 'method' => 'get', 'role' => "search", 'class' => "search-box" ] );
                    echo HTML::wrap(
                        Form::input( 'q', '', [ 'class' => "form-control", 'placeholder' => __( 'Search' ) ] )
                        . HTML::wrap(
                            Form::button( 'submit', __( 'Where is it?' ), [ 'class' => 'btn btn-secondary', 'style' => 'margin:0;padding:.375rem 1rem;font-size:1rem;line-height:1.5;' ] ),
                            [ 'class' => 'input-group-btn' ],
                            'span'
                        ),
                        [ 'class' => "input-group" ]
                    );
                    echo Form::close();
                ?>
            </div>
        </div>
    </div>
</section>