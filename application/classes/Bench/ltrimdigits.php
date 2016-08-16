<?php

// classes/bench/ltrimdigits.php
class Bench_LtrimDigits extends Codebench {
 
    // Some optional explanatory comments about the benchmark file.
    // HTML allowed. URLs will be converted to links automatically.
    public $description = 'Chopping off leading digits: regex vs ltrim.';
 
    // How many times to execute each method per subject.
    // Total loops = loops * number of methods * number of subjects
    public $loops = 100;
 
    // The subjects to supply iteratively to your benchmark methods.
    public $subjects = array
    (
        '123digits',
        'no-digits',
    );
    public function bench_map($subjects) {
        return array_map( 
                        function( $str ){ return basename( $str, '.php' ); }, 
                        Kohana::list_files( 'i18n', [ APPPATH ] )
                    );
    }
    
    public function bench_var($subjects) {
        return $subjects;
    }

//    public function bench_regex($subject)
//    {
//        return preg_replace('/^\d+/', '', $subject);
//    }
// 
//    public function bench_ltrim($subject)
//    {
//        return ltrim($subject, '0..9');
//    }
}