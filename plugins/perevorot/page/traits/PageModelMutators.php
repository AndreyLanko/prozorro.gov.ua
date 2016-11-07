<?php namespace Perevorot\Page\Traits;

use Cms\Classes\Page as CmsPage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Request;
use Perevorot\Page\Classes\PageHelpers;
use Perevorot\Page\Models\Page;
use RainLab\Translate\Classes\Translator;
use Session;

trait PageModelMutators
{
    /**
     * Get type icon by type.
     *
     * @return string
     */
    public function getTypeIconAttribute()
    {
        $icon='';

        switch($this->type)
        {
            case self::PAGE_TYPE_STATIC:
                $icon='oc-icon-file-text-o';
                break;

            case self::PAGE_TYPE_CMS:
                $icon='oc-icon-magic';
                break;

            case self::PAGE_TYPE_ALIAS:
                $icon='oc-icon-link';
                break;

            case self::PAGE_TYPE_EXTERNAL:
                $icon='oc-icon-external-link';
                break;
        }

        return $icon;
    }

    public function setUrlAttribute($value)
    {
        switch ($this->type)
        {
            case $this::PAGE_TYPE_CMS: {
                $pages = CmsPage::sortBy('baseFileName');
                $fileName = $this->cms_page_id;

                $page = array_first($pages, function ($key, $item) use ($fileName) {
                    return $item->baseFileName == $fileName;
                });

                if ($page) {
                    $url = (!$value)?$page->url:$value;
                    $this->attributes['url'] = $url;
                }
            }
                break;
            default:
                $this->attributes['url'] = $value;
        }
    }

    /**
     * Get url by type.
     *
     * @return string
     */
    public function getPageUrlAttribute()
    {
        $href='';

        switch($this->type)
        {
            case $this::PAGE_TYPE_STATIC:
                $href=$this->url;

                break;

            case $this::PAGE_TYPE_CMS:
                $pages=CmsPage::sortBy('baseFileName');
                $fileName=$this->cms_page_id;

                $page=array_first($pages, function($key, $item) use ($fileName){
                    return $item->baseFileName==$fileName;
                });

                if($page)
                    $href=$page->url;

                break;

            case $this::PAGE_TYPE_ALIAS:
                $href=!empty($this->alias_page->pageUrl) ? $this->alias_page->pageUrl : '';

                break;

            case $this::PAGE_TYPE_EXTERNAL:
                $href=$this->url_external;

                break;
        }

        return $href;
    }

    /**
     * @param $url
     */
    public function getUrlAttribute($url)
    {
        switch ($this->type) {
            case Page::PAGE_TYPE_ALIAS:
                return PageHelpers::getLocalizedUrl($this->alias_page->url);
            default:
                return $url;
        }
    }

    /**
     * Get localized url by type.
     *
     * @return string
     */
    public function pageUrlLocalized($locale, $default)
    {
        $href=$this->pageUrl;

        if($this->type!=self::PAGE_TYPE_EXTERNAL)
            $href=(!$default ? '/'.$locale : '').$href;

        return $href;
    }

    /**
     * @return bool
     */
    public function getIsDisplayPageAttribute()
    {
        $refererHeader = Request::header('referer');
        $serverName = Request::header('host');

        return (($this->is_closed_by_direct_open && $refererHeader && (starts_with($refererHeader, 'http://' . $serverName) || starts_with($refererHeader, 'https://' . $serverName))) || !$this->is_closed_by_direct_open)?true:false;
    }
}
