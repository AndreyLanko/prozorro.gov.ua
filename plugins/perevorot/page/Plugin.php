<?php namespace Perevorot\Page;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Perevorot\Page\Console\PageBuilder;
use Perevorot\Page\Console\RemoveLongreadBlocksFromDatabase;
use RainLab\Translate\Models\Locale as LocaleModel;
use RainLab\Translate\Classes\Translator;
use Illuminate\Support\Facades\Response;
use Perevorot\Page\Classes\PageHelpers;
use Perevorot\Page\Classes\PageCache;
use Perevorot\Page\Models\Menu;
use System\Classes\PluginBase;
use Cms\Classes\CmsException;
use Cms\Classes\Page;
use Perevorot\Page\Models\Page as PageModel;
use Backend;
use Request;
use Session;
use Event;
use Cache;
use File;
use View;
use App;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'Perevorot\Page\Components\LocalePicker' => 'localePicker',
            'Perevorot\Page\Components\MainMenu' => 'mainMenu',
            'Perevorot\Page\Components\SubMenu' => 'subMenu'
        ];
    }

    public function registerSettings()
    {
        return [
        ];
    }

    public function registerNavigation()
    {
        $menu=Menu::get();

        $sideMenu=[];

        $k = null;

        foreach($menu as $k=>$group)
        {
            $sideMenu['perevorot-page-menu'.$group->id]=[
                'label'=>$group->title,
                'url'=>Backend::url('perevorot/page/pages/?menu='.$group->id),
                'icon'=>'icon-files-o',
                'permissions'=>['perevorot.page.page'],
                'order'=>$k
            ];
        };

        $sideMenu['perevorot-page-menugroup']=[
            'label'=>'perevorot.page::lang.plugin.menu',
            'url'=>Backend::url('perevorot/page/menu'),
            'icon'=>'icon-sitemap',
            'permissions'=>['perevorot.page.page'],
            'order'=>$k+1
        ];

        return [
            'perevorot-page-main' => [
                'label'=>'perevorot.page::lang.plugin.name',
                'url'=>head($sideMenu)['url'],
                'icon'=>'icon-files-o',
                'permissions'=>['perevorot.page.page'],
                'order'=>10,
                'sideMenu'=>$sideMenu
            ],
        ];
    }

    private $defaultLocale;
    private $locale;

    public function boot()
    {
        $tableName = 'perevorot_page_page';

        Event::listen('eloquent.created: RainLab\Translate\Models\Locale', function ($model) use ($tableName) {
            Schema::table($tableName, function(Blueprint $table) use ($model, $tableName) {
                $column = 'longread_' . $model->code;

                if (!Schema::hasColumn($tableName, $column)) {
                    $table->text($column)->nullable();
                }
            });
        });

        Event::listen('eloquent.deleted: RainLab\Translate\Models\Locale', function ($model) use ($tableName) {
            Schema::table($tableName, function(Blueprint $table) use ($model, $tableName) {
                $column = 'longread_' . $model->code;

                if (Schema::hasColumn($tableName, $column)) {
                    $table->dropColumn($column);
                }
            });
        });

        Event::listen('cms.page.display', function($controller, $url, $page, $result)
        {
            if(env('CACHE_ENABLED', false) && !empty($result))
                Cache::put(PageCache::getCacheKey(), $result, PageCache::getCacheMinutes());
        });

        Event::listen('cms.page.beforeDisplay', function($controller, $url, $page)
        {
            $pageObject=PageModel::where('url', '/'.$url)->first();

            $cache=env('CACHE_ENABLED', false);

            if($pageObject && $pageObject->is_cache_ignore)
                $cache=false;

            if($cache && Cache::has(PageCache::getCacheKey()))
                return Response::make(Cache::get(PageCache::getCacheKey()));

            $translator=Translator::instance();
            $originalUrl=Request::path();
            $this->locale=$translator->getDefaultLocale();
    
            foreach(LocaleModel::listEnabled() as $locale=>$name)
            {
                if(starts_with($originalUrl, $locale.'/') || $originalUrl===$locale)
                    $this->locale=$locale;
            }
    
            $translator->setLocale($this->locale);

            $this->defaultLocale=$translator->getDefaultLocale();

            Event::listen('seo.handle', function ($seo) use ($pageObject) {
                $seo->setData([
                    'page' => $pageObject,
                ]);
            });

            if ($pageObject && !$pageObject->is_display_page) {
                return Response::make(View::make('cms::404'), 404);
            }

            if(!empty($page->settings['is_hidden']))
                return Response::make(View::make('cms::404'), 404);

            if(starts_with(Request::path(), $this->defaultLocale.'/') || Request::path()==$this->defaultLocale)
                return Response::make(View::make('cms::404'), 404);
        });

        Event::listen('cms.page.beforeRenderPage', function($controller, $page)
        {
            $fileName=$page->fileName;

            if($this->locale!=$this->defaultLocale)
            {
                $fileName = substr_replace($fileName, '.'.$this->locale, strrpos($fileName, '.'), 0);

                if (($page = Page::loadCached($controller->getTheme(), $fileName)) !== null)
                {
                    $page->url=substr($page->url, 3);

                    $controller->runPage($page);

                    return $controller->renderPage();
                }
            }
        });
    }

    public function registerMarkupTags()
    {
        return [
            'filters' => [
                'url' => [$this, 'getLocalizedUrl']
            ],
        ];
    }

    public function getLocalizedUrl($url)
    {
        return PageHelpers::getLocalizedUrl($url);
    }

    public function register()
    {
        $this->registerConsoleCommand('perevorot.page.build', PageBuilder::class);
        $this->registerConsoleCommand('perevorot.blocks.remove', RemoveLongreadBlocksFromDatabase::class);
    }

    public function registerFormWidgets()
    {
        return [
            'Perevorot\Page\FormWidgets\Widgets\Handsontable' => [
                'label' => 'Handsontable',
                'code' => 'handsontable',
            ]
        ];
    }
}
