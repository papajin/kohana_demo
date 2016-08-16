<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Gallery controller
 *
 * @author Игорь
 */
class Controller_Front_Gallery extends Controller_Front_Index {

    public function action_index()
    {
        // Navigation tabs
        $tab_list = ORM::factory( 'Page' )
                        ->where( 'parent_id', '=', 19 )
                        ->where( 'status', '>', 0 )
                        ->order_by( 'order' )
                        ->find_all();
//        
//        $this->main[] = View::factory( 'front/tplt/front_tplt_tabs', 
//                                        [ 'list' => $tab_list ] );
        
        /**
         * Finding tags like {gallery id=1 tpl=2} in page content, parse them
         * for gallery params. Make galleries and replace placeholder tags with
         * the galleries.
         */
        $pattern = '/\{gallery(?:\s[\w]+=[\d|\w]+)+\s?\/?\}/';
        $matches = [];

        if ( preg_match_all( $pattern, $this->_page->content, $matches ) )
        {
            $galleries = [];
            foreach ( $matches[ 0 ] as $params )
            {
                $attributes = [];
                $pairs = explode( ' ', $params );
                foreach ( $pairs as $pair )
                {
                    $data = explode( "=", $pair );
                    if( count( $data ) > 1 )
                    {
                        $attributes[ $data[ 0 ] ] = $data[ 1 ];
                    }
                }
                $galleries[] = $this->build_gallery( $attributes );
            }

            $this->_page->content = View::factory( 'front/tplt/front_tplt_tabs', [ 'tabs' => $tab_list ] ) . EOL_HT
                                    . HTML::wrap( $this->_page->title, [ 'class' => 'text-xs-center title' ], 'h1' ) . EOL_HT
                                    . '<div class="separator"></div>' . EOL_HT
                                    . HTML::wrap( str_replace( $matches[ 0 ],
                                                            $galleries,
                                                            $this->_page->content ),
                                                [ 'class' => 'row grid-space-20' ] );
        }

        // Styles
        $this->styles[]  = 'magnific-popup';

        // Scripts
        $this->scripts[] = 'magnific-popup';
    }

    protected function alias()
    {
        $param = $this->request->param( 'id' );
        return ( $param )
                ? 'gallery_' . $param
                : 'gallery';
    }

    private function build_gallery( $param )
    {
        $tplt = sprintf( 'front/gallery/front_gallery_%s', ( $param[ 'tplt' ] ?? 'icon' ) ); // Default template, if not set in param
        return View::factory( $tplt, [
            'gallery' => ORM::factory( 'Gallery' )->where( 'id', '=', $param[ 'id' ] )->find(),
            'params' => $param
        ] );
    }
}