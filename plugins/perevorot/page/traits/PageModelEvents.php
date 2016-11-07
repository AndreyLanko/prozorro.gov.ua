<?php namespace Perevorot\Page\Traits;

use Illuminate\Support\Facades\Cache;
use Perevorot\Page\Classes\PageCache;
use Perevorot\Page\Classes\PageFile;
use Cms\Classes\Theme;
use File;
use Perevorot\Page\Components\MainMenu;
use Perevorot\Page\Components\SubMenu;

trait PageModelEvents
{
    var $theme;

    public function afterSave()
    {
        if($this->type==self::PAGE_TYPE_STATIC)
        {
            PageFile::save($this);
        }

        Cache::forget(self::PAGES_CACHE_KEY);

        if (PageCache::isNotFileDriver()) {
            Cache::tags(MainMenu::TAG_NAME, SubMenu::TAG_NAME)->flush();
        } else {
            Cache::flush();
        }
    }

    public function beforeDelete()
    {
        PageFile::delete($this->url);
    }

    public function beforeUpdate()
    {
        if($this->isDirty('url'))
            PageFile::delete($this->getOriginal()['url']);
    }

    public function beforeValidate()
    {
        if($this->type==self::PAGE_TYPE_ALIAS)
            $this->url='';
    }

    public function beforeSave()
    {
        PageFile::delete($this->url);

        switch($this->type)
        {
            case self::PAGE_TYPE_STATIC:
                $this->url_external=null;
                $this->alias_page_id=0;
                $this->cms_page_id=0;

                break;

            case self::PAGE_TYPE_CMS:
                $this->url_external=null;
                $this->alias_page_id=0;
                $this->url='';
                $this->layout='';

                break;

            case self::PAGE_TYPE_ALIAS:
                $this->url_external=null;
                $this->cms_page_id='';
                $this->url='';
                $this->content='';
                $this->meta_title='';
                $this->meta_description='';
                $this->layout='';

                break;

            case self::PAGE_TYPE_EXTERNAL:
                $this->cms_page_id='';
                $this->alias_page_id=0;
                $this->url='';
                $this->content='';
                $this->meta_title='';
                $this->meta_description='';
                $this->layout='';

                break;
        }
    }
}
