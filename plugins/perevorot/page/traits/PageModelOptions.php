<?php namespace Perevorot\Page\Traits;

use Illuminate\Support\Facades\Cache;
use Perevorot\Page\Classes\PageCache;
use Perevorot\Page\Models\Page;
use Cms\Classes\Page as CmsPage;
use Cms\Classes\Layout;
use Lang;
use ReflectionClass;

trait PageModelOptions
{
    public function getTypeOptions()
    {
        $dropdown=[];

        $class = new ReflectionClass(__CLASS__);

        $constants=array_where($class->getConstants(), function($key, $value){
            return starts_with($key, 'PAGE_TYPE_');
        });

        foreach($constants as $constant=>$id)
        {
            switch($id)
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

            $dropdown[constant('self::'.$constant)]=sprintf('<i class="%s"></i>%s', $icon, Lang::get('perevorot.page::lang.form.page.types.'.strtolower(substr($constant, 10))));
        }

        return $dropdown;
    }

    public function getCmsPageIdOptions()
    {
        $pages=CmsPage::sortBy('baseFileName');

        $dropdown=[
            '' => Lang::get('perevorot.page::lang.form.page.select_cms_page_option')
        ];

        foreach($pages as $value)
        {
            if(strpos(':', $value->url)===false && !starts_with($value->baseFileName, 'menu/'))
                $dropdown[$value->baseFileName] = $this->getCmsPageName($value).', '.$value->url;
        }

        return $dropdown;
    }

    public function getCmsPageName($page)
    {
        return !empty($page->title) ? $page->title : $page->baseFileName.'.htm';
    }

    public function getAliasPageIdOptions()
    {
        $dropdown=[
            '' => Lang::get('perevorot.page::lang.form.page.select_cms_page_option')
        ];

        if(Cache::has(self::PAGES_CACHE_KEY))
            return Cache::get(self::PAGES_CACHE_KEY);

        $pages=Page::get();

        foreach($pages as $page)
            $dropdown[$page->id]=sprintf('<i class="%s" style="margin-left:%spx"></i>%s, %s, %s', $page->typeIcon, $page->nest_depth*20, $page->title, $page->pageUrl, $page->menu->title);

        if(!empty($dropdown))
            Cache::put(self::PAGES_CACHE_KEY, $dropdown, PageCache::getCacheMinutes());

        return $dropdown;
    }

    public function getLayoutOptions()
    {
        $layouts=Layout::sortBy('baseFileName');
        $dropdown=[];

        foreach($layouts as $filename=>$layout)
            $dropdown[$filename] = $layout->fileName;

        return $dropdown;
    }

}
