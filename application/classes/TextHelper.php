<?php

/**
 * Usefull functions of text processing/formating
 *
 * @author Ihor
 */
class TextHelper {
    
    /**
     * Cuts $text with $radius chars before and after search needles ($phrase).
     * Ends the returned string with $ending 
     * and highlights the $phrase if $highlight set TRUE (default).
     * @param string $text
     * @param string $phrase
     * @param bool $highlight
     * @param int $radius
     * @param string $ending
     * @return string
     */
    public static function excerpt ($text, $phrase, $highlight = TRUE, $radius = 100, $ending = '&hellip;') 
    {
        $phraseLen = strlen($phrase);
        if ($radius < $phraseLen) {
            $radius = $phraseLen;
        }

        $pos = strpos(strtolower($text), strtolower($phrase));

        $startPos = 0;
        if ($pos > $radius) {
            $startPos = $pos - $radius;
        }

        $textLen = strlen($text);

        $endPos = $pos + $phraseLen + $radius;
        if ($endPos >= $textLen) {
            $endPos = $textLen;
        }

        $excerpt = substr($text, $startPos, $endPos - $startPos);
        if ($startPos != 0) {
            $excerpt = UTF8::substr_replace($excerpt, $ending, 0, $phraseLen);
        }

        if ($endPos != $textLen) {
            $excerpt = UTF8::substr_replace($excerpt, $ending, -$phraseLen);
        }
        
        if ( $highlight )
            $excerpt = self::highlight($phrase, $excerpt);

        return  $excerpt;
    }
    
    public static function highlight($needle, $haystack)
    { 
        $ind = stripos($haystack, $needle); 
        $len = strlen($needle); 
        if($ind !== false){ 
            return substr($haystack, 0, $ind) . "<b>" . substr($haystack, $ind, $len) . "</b>" . 
                self::highlight($needle, substr($haystack, $ind + $len)); 
        }
        else return $haystack; 
    }
    
    public static function improveImg($param) 
    {
        unlink($param);
        rmdir(substr($param, 0, strrpos($param, DIRECTORY_SEPARATOR)));
    }
    
    /**
     * Функция делает вступительный анонс из переданного текста, удаляя тэги. 
     * @param string $text  - текст
     * @param integer $length   - длина анонса
     * @return string
     */
    public static function makeIntro($text, $length = 100, $ending = '&hellip;')
    {
        $text = strip_tags($text);
        return ( $length >= strlen($text) )
             ? $text
             : UTF8::substr($text, 0, $length).$ending;
    }
    
    /**
     * Replaces cyrilic letters with corresponding latin ones in the passed @string.
     * @param string $string the text to be transliterated (cyrilic string)
     * @return string transliterated string (latin string)
     */
    public static function transliterate($string)
    {
	$replace=array(
		'\''=>'',','=>'',
		'`'=>'','.'=>'',
                '\"'=>'','!'=>'',
                '('=>'',')'=>'',
                '?'=>'',' '=>'_',
		'а'=>'a','А'=>'a',
		'б'=>'b','Б'=>'b',
		'в'=>'v','В'=>'v',
		'г'=>'g','Г'=>'g',
		'д'=>'d','Д'=>'d',
		'е'=>'e','Е'=>'e',
		'ж'=>'zh','Ж'=>'zh',
		'з'=>'z','З'=>'z',
		'и'=>'i','И'=>'i',
		'й'=>'y','Й'=>'y',
		'к'=>'k','К'=>'k',
		'л'=>'l','Л'=>'l',
		'м'=>'m','М'=>'m',
		'н'=>'n','Н'=>'n',
		'о'=>'o','О'=>'o',
		'п'=>'p','П'=>'p',
		'р'=>'r','Р'=>'r',
		'с'=>'s','С'=>'s',
		'т'=>'t','Т'=>'t',
		'у'=>'u','У'=>'u',
		'ф'=>'f','Ф'=>'f',
		'х'=>'h','Х'=>'h',
		'ц'=>'c','Ц'=>'c',
		'ч'=>'ch','Ч'=>'ch',
		'ш'=>'sh','Ш'=>'sh',
		'щ'=>'sch','Щ'=>'sch',
		'ъ'=>'','Ъ'=>'',
		'ы'=>'y','Ы'=>'y',
		'ь'=>'','Ь'=>'',
		'э'=>'e','Э'=>'e',
		'ю'=>'yu','Ю'=>'yu',
		'я'=>'ya','Я'=>'ya',
		'і'=>'i','І'=>'i',
		'ї'=>'yi','Ї'=>'yi',
		'є'=>'e','Є'=>'e',
	);
	return iconv('UTF-8','UTF-8//IGNORE',strtr($string,$replace));
    }
    
    /**
     * Extract src attribute of the first image of the @post.
     * @param string $post html with supposed image.
     * @return mixed src attribute of the first image, 0 - if no image found or false - if error
     */
    public static function get_first_image_src($post)
    {
        $output = preg_match('/src="(?P<src>[^"]+)/i', $post, $image);

        return ($output)
                    ? $image['src']
                    : $output;
    }
}