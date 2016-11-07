<?php namespace Perevorot\Page\Classes;

use App;

class PageHelpers
{
    private static $defaultLocale;
    
    static function getLocalizedUrl($url)
    {
        if(!self::$defaultLocale)
            self::$defaultLocale=\RainLab\Translate\Models\Locale::getDefault();

        $locale=App::getLocale();
        $url=trim(trim($url), '/');

        if($locale!=self::$defaultLocale->code)
            $url=$locale.'/'.$url;
            
        return '/'.$url;            
    }
}
