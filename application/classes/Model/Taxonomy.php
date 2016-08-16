<?php defined('SYSPATH') or die('No direct script access.');
class Model_Taxonomy extends ORM {
    protected $_table_name = 'term_taxonomy';
    protected $_primary_key = 'term_taxonomy_id';
    protected $_has_one = [
        'term' => [
            'model'         => 'Term',
            'foreign_key'   => 'term_id'
        ],
    ];
}
