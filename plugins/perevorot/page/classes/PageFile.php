<?php namespace Perevorot\Page\Classes;

use Cms\Classes\Theme;
use ApplicationException;
use Lang;
use File;

class PageFile
{
    static $theme;
    static $locales;

    static $pageAttributes=[
        'title',
        'url',
        'layout',
        'meta_title',
        'meta_description',
        'is_disabled',
        'is_hidden',
        'is_closed_by_direct_open',
        'is_cache_ignore'
    ];

    public static function save($model)
    {
        self::getTheme();

        foreach(self::getLocaleCodes() as $localeCode)
            self::saveByLocale($model, $localeCode);
    }

    public static function delete($url)
    {
        self::getTheme();

        foreach(self::getLocaleCodes() as $localeCode)
            self::deleteByLocale($url, $localeCode);
    }

    private static function saveByLocale($model, $localeCode)
    {
        if(!empty($model->url))
        {
            self::getTheme();

            $fullPath=self::getFullPath($model->url, $localeCode);
            $dirPath=self::getDirPath();

            if(File::isFile($fullPath) && !is_dir($fullPath) && !@unlink($fullPath))
            {
                throw new ApplicationException(Lang::get('cms::lang.cms_object.error_deleting', [
                    'name'=>self::getClearFileName($model->url, $localeCode)
                ]));
            }

            if(!is_dir($dirPath) && !File::makeDirectory($dirPath, 0777, true, true))
            {
                throw new ApplicationException(Lang::get('cms::lang.cms_object.error_creating_directory', [
                    'name'=>$dirPath
                ]));
            }

            if (@File::put($fullPath, self::getPage($model, $localeCode)) === false)
            {
                throw new ApplicationException(Lang::get('cms::lang.cms_object.error_saving', [
                    'name'=>self::getClearFileName($model->url, $localeCode)
                ]));
            }

            clearstatcache();
        }
    }

    private static function deleteByLocale($url, $localeCode)
    {
        if(!empty($url))
        {
            $fullPath=self::getFullPath($url, $localeCode);

            if (File::isFile($fullPath) && !is_dir($fullPath) && !@unlink($fullPath))
            {
                throw new ApplicationException(Lang::get('cms::lang.cms_object.error_deleting', [
                    'name'=>self::getClearFileName($url, $localeCode)
                ]));
            }

            clearstatcache();
        }
    }

    private static function getPage($model, $localeCode)
    {
        $output=[];

        foreach(self::$pageAttributes as $attribute)
        {
            $value=self::getAttributeValue($model, $attribute, $localeCode);

            $output[]=sprintf('%s = "%s"', $attribute, self::parseValue($attribute, $value, $localeCode));
        }

        $output[]='';
        $output[]='[Perevorot\Longread\Components\Longread longread]';
        $output[]='==';
        $output[]="{% component 'longread' %}";

        return implode("\n", $output);
    }

    private static function parseValue($attribute, $value, $localeCode)
    {
        return ($attribute=='url' && !self::isDefaultLocale($localeCode)) ? '/'.$localeCode.$value : $value;
    }

    private static function getAttributeValue($model, $attribute, $localeCode)
    {
        return self::isTranslatable($model, $attribute) ? $model->noFallbackLocale()->getAttributeTranslated($attribute, $localeCode) : $model->$attribute;
    }

    private static function isTranslatable($model, $column)
    {
        return in_array($column, $model->getTranslatableAttributes());
    }

    private static function getTheme()
    {
        self::$theme=Theme::getActiveTheme();
    }

    private static function getDirPath()
    {
        return self::$theme->getPath().'/pages/menu/';
    }

    private static function getFullPath($url, $localeCode)
    {
        return self::getDirPath().self::getClearFileName($url, $localeCode);
    }

    private static function getClearFileName($url, $localeCode)
    {
        return str_replace('/', '-', trim($url, '/')).self::getLocaleFileSuffix($localeCode).'.htm';
    }

    private static function getLocaleFileSuffix($localeCode)
    {
        return !self::isDefaultLocale($localeCode) ? '.'.$localeCode : '';
    }

    private static function hasTranslatableModel()
    {
        return class_exists('RainLab\Translate\Models\Locale');
    }

    private static function getLocaleCodes()
    {
        return array_pluck(self::getLocales(), 'code');
    }

    private static function getLocales()
    {
        if(empty(self::$locales))
            self::$locales=\RainLab\Translate\Models\Locale::get();

        return self::$locales;
    }

    private static function isDefaultLocale($code)
    {
        return array_first(self::getLocales(), function($key, $locale) use ($code){
            return $locale->code==$code;
        })->is_default;
    }
}
