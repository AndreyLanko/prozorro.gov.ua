<?php namespace Perevorot\Page\Components;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;
use Perevorot\Page\Classes\PageCache;
use Perevorot\Page\Models\Page as PageModel;

class SubMenu extends MainMenu
{
    CONST KEY = '_submenu';
    const TAG_NAME = 'menu_submenu';

    use \Perevorot\Page\Components\Traits\MenuUtils;

    public function componentDetails()
    {
        return [
            'name' => 'perevorot.page::components.submenu.component.name',
            'description' => 'perevorot.page::components.submenu.component.description',
            'icon'=>'icon-files-o',
        ];
    }

    public function onRender()
    {
        $this->depthStart=(int)$this->property('depthStart');
        $this->depthMax=(int)$this->property('depthMax');

        $currentPage = PageModel::where('url', '=', $this->page->url)->first();

        if ($currentPage->parent && $currentPage->parent->type == 3) {
            $currentPage = $currentPage->parent;
        }

        $params = [];

        if($currentPage)
        {
            $rootPage=PageModel::where('nest_left', '<=', $currentPage->nest_left)->where('nest_right', '>=', $currentPage->nest_right)->orderBy('nest_left', 'ASC')->first();

            $menu=Cache::remember(self::KEY.'_'.md5(json_encode($rootPage->toArray()).'_'.$this->property('menu').'_'.$this->depthStart . '_' . Lang::getLocale()), 60, function() use ($currentPage, $rootPage)
            {
                $menuModel=PageModel::enabled($this->property('menu'));
    
                $menuModel->where('id', '!=', $rootPage->id);
                $menuModel->where('nest_left', '>=', $rootPage->nest_left);
                $menuModel->where('nest_right', '<=', $rootPage->nest_right);
                $menuModel->where('nest_depth', '=', $rootPage->nest_depth+$this->depthStart);
                $menuModel->where('is_hidden', '=', false);
                $menuModel->where('is_disabled', '=', false);
                $menuModel->orderBy('nest_left', 'ASC');
                $menuModel->with('children');

                $menu=$menuModel->get();

                foreach($menu as $k=>$item)
                {
                    if(!empty($item->children))
                    {
                        foreach($item->children as $kk=>$child)
                        {
                            if($child->is_hidden || $child->is_disabled)
                                unset($item->children[$kk]);
                        }
                    }
                }

                $this->toObject($menu);

                $menu = $this->parseMenu($menu, $this->depthStart);

                return $menu;
            });

            $this->activeMenuCheck($menu);

            $params = [
                'depthStart' => $this->depthStart,
                'depthMax' => $this->depthMax,
                'menu' => $menu,
                'type' => $this->property('type'),
            ];
        }

        $result = $this->renderMenu($this->property('partial'), $params);

        return $result;
    }

    public function defineProperties()
    {
        $properties=$this->definedProperties;

        $properties['depthStart'] = [
            'title' => 'perevorot.page::components.submenu.properties.depthStart.name',
            'description' => 'perevorot.page::components.submenu.properties.depthStart.description',
            'type' => 'dropdown',
        ];

        $properties['type'] = [
            'title' => 'perevorot.page::components.submenu.properties.type.name',
            'description' => 'perevorot.page::components.submenu.properties.type.description',
            'type' => 'dropdown',
        ];

        return $properties;
    }

    public function getDepthStartOptions()
    {
        $max_depth=PageModel::max('nest_depth');

        $options=[
            1=>trans('perevorot.page::components.submenu.properties.depthStart.top')
        ];

        for($k=2; $k<=$max_depth; $k++)
            $options[$k]=$k;

        return $options;
    }

}
