<?php namespace Perevorot\Page\Components;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;
use Perevorot\Page\Classes\PageCache;
use Perevorot\Page\Models\Menu as MenuModel;
use Perevorot\Page\Models\MenuSettings;
use Perevorot\Page\Models\Page as PageModel;
use RainLab\Translate\Classes\Translator;
use Cms\Classes\ComponentBase;

class MainMenu extends ComponentBase
{
    const KEY = '_mainmenu';
    const TAG_NAME = 'menu_mainmenu';

    use \Perevorot\Page\Components\Traits\MenuUtils;

    public $translator;
    public $activeLocale;
    public $isDefaultLocale;
    public $depthStart;
    public $depthMax;

    public $definedProperties=[
        'menu' => [
            'title' => 'perevorot.page::components.menu.properties.menu.name',
            'description' => 'perevorot.page::components.menu.properties.menu.description',
            'type' => 'dropdown',
            'validation' => [
                'required' => [
                    'message'=> 'perevorot.page::components.menu.properties.menu.required'
                ]
            ]
        ],
        'depthMax' => [
            'title' => 'perevorot.page::components.menu.properties.depthMax.name',
            'description' => 'perevorot.page::components.menu.properties.depthMax.description',
            'type' => 'dropdown',
        ],
        'partial' => [
            'title' => 'perevorot.page::components.menu.properties.partial.name',
            'description' => 'perevorot.page::components.menu.properties.partial.description',
        ]
    ];

    public function init()
    {
        $this->translator = Translator::instance();
        $this->activeLocale = $this->translator->getLocale();
        $this->isDefaultLocale = $this->activeLocale==$this->translator->getDefaultLocale();
    }

    public function onRender()
    {
        $menu = Cache::remember(self::KEY.'_'.$this->property('menu') . '_' . Lang::getLocale(), 60, function(){
            $menu = PageModel::enabled($this->property('menu'))->with('children')->whereNull('parent_id')->get()->filter(function ($item) {
                return !empty($item->title);
            });

            $this->toObject($menu);

            return $menu;
        });

        $this->activeMenuCheck($menu);

        $params = [
            'depthMax' => $this->property('depthMax'),
            'menu' => $menu
        ];

        $result = $this->renderMenu($this->property('partial'), $params);

        return $result;
    }

    public function componentDetails()
    {
        return [
            'name' => 'perevorot.page::components.menu.component.name',
            'description' => 'perevorot.page::components.menu.component.description',
            'icon'=>'icon-files-o',
        ];
    }

    public function defineProperties()
    {
        return $this->definedProperties;
    }

    public function getMenuOptions()
    {
        $menu=MenuModel::get();
        $options=[];

        foreach($menu as $one)
            $options[$one->id]=$one->title;

        return $options;
    }

    public function getDepthMaxOptions()
    {
        $max_depth=PageModel::max('nest_depth');

        $options=[
            ''=>trans('perevorot.page::components.menu.properties.depthMax.all')
        ];

        for($k=1; $k<=$max_depth; $k++)
            $options[$k]=$k;

        return $options;
    }
}
