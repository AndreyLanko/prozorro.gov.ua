<?php namespace Perevorot\Page\Classes;

use Request;

class PageCache
{
    static function getCacheKey()
    {
        return str_slug('page_'.Request::url());
    }
    
    static function getCacheMinutes()
    {
        return env('CACHE_MINUTES', 60);
    }

    public static function isNotFileDriver()
    {
        $env = env('CACHE_DRIVER');

        return ($env != "file") && ($env != "database");
    }
}
