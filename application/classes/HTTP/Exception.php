<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Exception handling class
 *
 * @author Игорь
 */

class HTTP_Exception extends Kohana_HTTP_Exception {
    /**
     * Generate a Response for the exception.
     *
     * @return Response
     */
    public function get_response()
    {
        // Standard process while development
        if ( Kohana::DEVELOPMENT === Kohana::$environment )
        {
            parent::get_response();
        }
        else
        {
            // Log it
            Kohana::$log->add( Log::ERROR, parent::text( $this ) );


            $code = $this->getCode();

            /* Special controller for exceptions */
            $response = Request::factory( 'error/index/' . $code )->execute();

            if( $code >= 500 )
            {
                // Inform admin about server error
                $settings = Kohana::$config->load( 'settings' );

                $msg = $this->getMessage().' <br />';
                $msg .= 'Initial uri: '.Request::initial()->uri().' <br />';
                $msg .= 'Referrer: '.Request::initial()->referrer().' <br />';

                Email::send( NULL, 'ERROR 500. Повідомлення з ivanets.com', $msg, $settings->author_email, $settings->admin_email );
            }

            // Return error page finally.
            return $response;
        }
    }
}