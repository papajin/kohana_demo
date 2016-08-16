<?php defined('SYSPATH') or die('No direct script access.');

    /* Vars for some code relief */
    $show_success = ( !! Arr::get( $data, 'submit' ) AND empty( $errors ) );
    $show_errors = ( !! Arr::get( $data, 'submit' ) AND ! empty( $errors ) );
    $i = '<i class="icon-info text-info"></i>';
    $lbl_cls = 'col-sm-3 form-control-label';
    $c_span = '<span class="c-indicator"></span>';
    $d_cls = 'col-sm-9';
    $zebra = __( 'zebra_datepicker' );
?>
<!-- main-container start -->
<!-- ================ -->
<section class="main-container">

    <div class="container">
        <div class="row">

            <!-- main start -->
            <!-- ================ -->
            <div class="main col-md-12" role="main">

                <?= $page->content; ?>

                <div class="alert alert-success<?php print ( $show_success ) ? '' : ' collapse'; ?>" id="MessageSent">
                    <?= __( 'Thank you! We have received your inquiry and will contact you very soon.' ); ?>
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

                <?=Form::open( NULL, [  'method' => 'post',
                                        'id'     => 'nq',
                                        'name'   => 'anketa',
                                        'role'   => 'form' ] );?>

                    <div class="form-group row">
                        <?=Form::label('name', sprintf( '%s %s:', $i, __( 'Name, surname' ) ),
                            [ 'class' => $lbl_cls, 'title' => __( 'Your name or nickname' ), 'data-toggle' => 'tooltip' ] );?>
                        <div class="<?= $d_cls; ?>">
                            <?= Form::input(
                                    'name',
                                    Arr::get( $data, 'name' ),
                                    [ 'id'=>'name', 'placeholder' => __( 'Your name or nickname' ), 'required'=>1, 'class'=>'form-control' ]
                                ); ?>
                        </div>

                    </div>
                    <div class="form-group row">
                        <?=Form::label('day', sprintf( '%s %s:', $i, __( 'Date of birth' ) ),
                            [ 'class' => $lbl_cls, 'title' => __( 'Specify the date of birth. If datepicker is not working in your browser, follow "yyyy-mm-dd" format (for example, 2012-12-21)' ), 'data-toggle' => 'tooltip' ] ); ?>
                        <div class="<?= $d_cls; ?>">
                            <div class="row form-inline">
                                <?= Form::label('date',
                                    Form::input(
                                        'date',
                                        Arr::get( $data, 'date' ),
                                        [ 'id' => 'date', 'class'=>'form-control', 'type'=>'date', 'style' => 'line-height:1.5rem', 'required' => 1 ]
                                    ),
                                    [ 'class' => 'col-sm-5' ] ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?= Form::label( 'time_delta', __( 'Time of birth' ),
                            [ 'class' => $lbl_cls ] ) ;?>
                        <div class="<?= $d_cls; ?>">
                            <div class="row">
                                <div class="col-sm-7">
                                    <?= Form::label( 'time_delta',
                                        sprintf( '%s %s', $i, __( 'I know the time of birth with precision of' ) ),
                                        [ 'title' => __( 'Select the precision with which you know the time of birth' ), 'data-toggle' => 'tooltip' ] ); ?>
                                    <?= Form::select( 'time_delta',
                                            Arr::get( $nq, 'time_delta' ),
                                            Arr::get( $data, 'time_delta' ),
                                            [ 'id'=>'time_delta', 'class'=>'form-control', 'required' => 1 ] ); ?>
                                </div>

                                <div class="col-sm-5">
                                    <?= Form::label('time',
                                        sprintf( '%s %s', $i, __( 'Time of birth' ) ),
                                        [ 'title' => __( 'Specify the approximate time of birth in "hh:mm" format, which you may know from your mother or one indicated on the hospital label' ), 'data-toggle' => 'tooltip' ] ); ?>
                                    <?= Form::input( 'time',
                                            Arr::get( $data, 'time' ),
                                            [ 'id' => 'time', 'type' => 'time', 'class' => 'form-control', 'style' => 'line-height:1.5rem' ] ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?= Form::label( 'born_at', sprintf( '%s %s:', $i, __( 'Place of birth' ) ),
                            [ 'class' => $lbl_cls, 'title' => __( 'Specify the state, region, district, town' ), 'data-toggle' => 'tooltip' ] ) ;?>
                        <div class="<?= $d_cls; ?>">
                            <?= Form::input(
                                'born_at',
                                Arr::get( $data, 'born_at' ),
                                [ 'id'=>'born_at', 'placeholder' => __( 'Antigo, Wisconsin, USA' ), 'class'=>'form-control', 'required' => 1 ]
                            ); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?= Form::label( 'live_at', sprintf( '%s %s:', $i, __( 'Location now' ) ),
                            [ 'class' => $lbl_cls, 'title' => __( 'Specify the state, region, district, town' ), 'data-toggle' => 'tooltip' ] ) ;?>
                        <div class="<?= $d_cls; ?>">
                            <?= Form::input(
                                'live_at',
                                Arr::get( $data, 'live_at' ),
                                [ 'id'=>'live_at', 'placeholder' => __( 'Antigo, Wisconsin, USA' ), 'class'=>'form-control', 'required' => 1 ]
                            ); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?= Form::label( 'gender_m', __( 'Gender' ) . ':', [ 'class' => $lbl_cls ] ) ; ?>
                        <div class="<?= $d_cls; ?> form-inline">
                            <?= Form::label( 'gender_m', sprintf( '%s - %s%s', Form::radio( 'gender', 'm', Arr::get( $data, 'gender' ) == 'm',[ 'id'=>'gender_m', 'required' => 1 ] ), __( 'male' ), $c_span ), [ 'class' => 'c-input c-radio' ] );?>
                            <?= Form::label( 'gender_f', sprintf( '%s - %s%s', Form::radio( 'gender', 'f', Arr::get( $data, 'gender' ) == 'f', [ 'id'=>'gender_f' ] ), __( 'female' ), $c_span ), [ 'class' => 'c-input c-radio' ] );?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?= Form::label( 'tall', sprintf( '%s %s:', $i, __( 'Height' ) ),
                            [ 'class' => $lbl_cls, 'title' => __( 'Enter your height in cm' ), 'data-toggle' => 'tooltip' ] ) ;?>
                        <div class="<?= $d_cls; ?> input-group">
                            <?= Form::input(
                                'tall',
                                Arr::get( $data, 'tall' ),
                                [ 'id'=>'tall', 'placeholder' => 168, 'class'=>'form-control', 'type' => 'number', 'max' => 250, 'required' => 1 ]
                            ); ?>
                            <div class="input-group-addon"><?= __( 'cm' ); ?></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?= Form::label( 'weight', sprintf( '%s %s:', $i, __( 'Weight' ) ),
                            [ 'class' => $lbl_cls, 'title' => __( 'Enter your weight in kg' ), 'data-toggle' => 'tooltip' ] ) ;?>
                        <div class="<?= $d_cls; ?> input-group">
                            <?= Form::input(
                                'weight',
                                Arr::get( $data, 'weight' ),
                                [ 'id'=>'weight', 'placeholder' => 68, 'class'=>'form-control', 'type' => 'number', 'max' => 250, 'required' => 1 ]
                            ); ?>
                            <div class="input-group-addon"><?= __( 'kg' ); ?></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?= Form::label( 'mes', sprintf( '%s %s:', $i, __( 'Your message' ) ),
                            [ 'class' => $lbl_cls, 'title' => __( 'Describe what you expect from counseling astrologer, your request' ), 'data-toggle' => 'tooltip' ] ) ;?>
                        <div class="<?= $d_cls; ?>">
                            <?= Form::textarea('mes', Arr::get( $data, 'mes' ), [ 'id' => 'mes', 'rows' => 5, 'class' => 'form-control', 'required'=>1 ] ) ;?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?= Form::label( 'email', sprintf( '%s %s:', $i, __( 'Email' ) ),
                            [ 'class' => $lbl_cls, 'title' => __( 'Enter your real email' ), 'data-toggle' => 'tooltip' ] ) ;?>
                        <div class="<?= $d_cls; ?>">
                            <?=Form::input( 'email',  Arr::get( $data, 'email' ), [ 'id' => 'email', 'class' => 'form-control', 'type' => 'email', 'placeholder'=>'your@email.com', 'required' => 1 ] ) ;?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="<?= $d_cls; ?> col-sm-offset-3">
                            <?= Form::submit( 'submit', __( 'Submit' ), [ 'class' => 'submit-button btn btn-default' ] ); ?>
                            <?=Form::input( 'reset', __( 'Clear entries' ), [ 'type' => 'reset', 'class' => 'btn btn-secondary' ] ); ?>
                        </div>
                    </div>
                <?=Form::close();?>
            </div>
        </div>
    </div>
</section>

<script>
    window.onload = function () {
        // Initiate Zebra DatePicker for those browsers, which have no own one.
        checkDateInput() || $('#date').Zebra_DatePicker({
                                            'view': 'years',
                                            'days_abbr': [<?= $zebra['days'];?>],
                                            'months': [<?= $zebra['months'];?>],
                                            'lang_clear_date': <?= $zebra['clear'];?>,
                                            'show_select_today': <?= $zebra['today'];?>
                                        });
    };

    function checkDateInput() {
        var input = document.createElement('input');
        input.setAttribute('type','date');

        var notADateValue = 'not-a-date';
        input.setAttribute('value', notADateValue);

        return (input.value !== notADateValue);
    }
</script>