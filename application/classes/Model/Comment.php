<?php defined('SYSPATH') or die('No direct script access.');
class Model_Comment extends ORM {

    protected $_primary_key = 'comment_ID';

    protected $_belongs_to = [
        'post' => [
            'model'       => 'Post',
            'foreign_key' => 'ID'
        ]
    ];

    /**
     * Shortening long comments to 100 chars adding "..." as ending.
     * Uses UTF8::strlen and UTF8::substr.
     * @param int $length optional number of chars for output string.
     * @return string comment excerpt.
     */
    public function short( $length = 400 )
    {
        $strlen = UTF8::strlen( $this->comment_content );

        // Comment not that long
        if ( $length * 1.1 >= $strlen )
            return $this->comment_content;

        if ( strrpos( $this->comment_content, '</' ) < $length )
            return  UTF8::substr( $this->comment_content, 0, $length * 1.1 ) . '&hellip;';

        return UTF8::substr( strip_tags( $this->comment_content ), 0, $length * 1.1 ) . '&hellip;';
    }
}