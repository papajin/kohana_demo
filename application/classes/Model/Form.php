<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Form validation and emailing done here.
 * User: Ihor
 * Date: 29.04.2016
 * Time: 10:50
 */
class Model_Form extends Model
{
    private $config, $submitted;

    public function __construct()
    {
        $this->config = Kohana::$config->load( 'form' );
        $this->submitted = Request::$initial->method() === HTTP_Request::POST;
        
        // TODO: check behavior with ajax request
        /* Known spammer is immediately given shit */
        if ( $this->submitted AND in_array( Request::$initial->post( 'email' ), $this->config[ 'blacklist' ] ) )
            Cookie::set( $this->config->spam_cookie_name, $this->config->spam_cookie_val, 3600 * 24 * 30 );         // 1 month ban on submiting forms

        if ( $this->config->spam_cookie_val === Cookie::get( $this->config->spam_cookie_name, FALSE ) )
            HTTP::redirect ( 'http://' . $this->config->shit[ rand ( 0, count ( $this->config->shit ) - 1 ) ] );    // See some dirty stuff
    }

    /**
     * @return bool TRUE if there was data sent by POST method
     */
    public function empty_post()
    {
        return ! $this->submitted;
    }

    /**
     * Config getter
     * @param string $name config name
     * @return mixed config value or NULL
     */
    public function get_config( $name )
    {
        return $this->config->get( $name );
    }

    /**
     * Processing of contact form (data validation and emailing).
     * @param array Config reference to Controller settings property
     * @param array $errors reference to container for errors
     * @return Validation object with POST data, if form submitted
     */
    public function contact( &$settings, Array &$errors )
    {
        if ( $this->submitted )
        {
            $post = Validation::factory( Request::$initial->post() );
            $post   ->rule( 'name', 'not_empty' )
                    ->rule( 'name', 'min_length', [ ':value', 3 ] )
                    ->rule( 'email', 'not_empty' )
                    ->rule( 'email', 'email' )
                    ->rule( 'comtext', 'not_empty' )
                    ->rule( 'is_spam', 'matches', [ ':validation', ':field', 'answer' ] )
                    ->labels( [ 'name' => 'Your name', 'email' => 'Your email', 'comtext' => 'Your message', 'is_spam' => 'Spam check' ] );

            if ( $post->check() ) {
                // Data valid, can send message
                $to = [ $settings->author_email => $settings->author ];
                $subj = __( 'Contact form message from %s', [ '%s', $post[ 'name' ] ] );
                try
                {
                    Email::send( NULL, $subj, $post[ 'comtext' ], $post[ 'email' ], $to )
                    OR $errors[] = __( 'Your message cannot be sent now. Please, try later.' );
                }
                catch ( Swift_TransportException $e )
                {
                    //                Kohana::$log->add(Log::INFO, print_r( [ $e->getMessage() ], TRUE ) );
                    $errors[] = $e->getMessage();
                }
            }
            else
            {
                $errors = Arr::flatten( Arr::merge( $errors, $post->errors( 'contact' ) ) );
            }
        }
        else
        {
            $post = [];
        }

        return $post;
    }

    public function anketa( &$settings, Array &$errors )
    {
        if ( $this->submitted )
        {
            $post = Validation::factory( Request::$initial->post() );
            $post   ->rule( 'name', 'not_empty' )
                    ->rule( 'date', 'not_empty' )->rule( 'date', 'date' )
                    ->rule( 'time_delta', 'not_empty' )
                    ->rule( 'time', 'date' )
                    ->rule( 'born_at', 'not_empty' )
                    ->rule( 'live_at', 'not_empty' )
                    ->rule( 'gender', 'not_empty' )
                    ->rule( 'tall', 'not_empty' )->rule( 'tall', 'numeric' )
                    ->rule( 'weight', 'not_empty' )->rule( 'weight', 'numeric' )
                    ->rule( 'mes', 'not_empty' )
                    ->rule( 'email', 'not_empty' )->rule( 'email', 'email' )
                    ->labels( [ 'name' => __( 'Name, surname' ), 'date' => __( 'Date of birth' ),
                        'time_delta' => sprintf( '%s / %s', __( 'I know the time of birth with precision of' ), __( 'Time of birth' ) ),
                        'time' => __( 'Time of birth' ), 'born_at' => __( 'Place of birth' ), 'live_at' => __( 'Location now' ),
                        'gender' => __( 'Gender' ), 'tall' => __( 'Height' ), 'weight' => __( 'Weight' ),
                        'mes' => __( 'Your message' ), 'email' => __( 'Email' ) ] );

            if ( $post->check() ) {
                // Data valid, can send message
                $to = [ $settings->author_email => $settings->author ];
                $subj = __( 'Consultation inquiry from %s', [ '%s' => $post[ 'name' ] ] );
                $mail_body = View::factory( 'front/misc/front_misc_nq_mail', [
                                                                                'title' => $subj,
                                                                                'data' => $post,
                                                                                'time_delta' => $this->config->get( 'nq' )['time_delta']
                                                                            ] );
                try
                {
                    Email::send( NULL, $subj, $mail_body, $post[ 'email' ], $to, 'text/html' )
                    OR $errors[] = __( 'Your message cannot be sent now. Please, try later.' );
                }
                catch ( Swift_TransportException $e )
                {
                    //                Kohana::$log->add(Log::INFO, print_r( [ $e->getMessage() ], TRUE ) );
                    $errors[] = $e->getMessage();
                }
            }
            else
            {
                $errors = Arr::flatten( Arr::merge( $errors, $post->errors( 'contact' ) ) );
            }
        }
        else
        {
            $post = [];
        }

        return $post;
    }
}