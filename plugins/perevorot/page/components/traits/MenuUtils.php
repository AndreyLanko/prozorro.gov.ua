<?php namespace Perevorot\Page\Components\Traits;

use Perevorot\Page\Models\Page;
use Request;

trait MenuUtils
{
    private function toObject(&$menu)
    {
        $menu = $menu->filter(function($page)
        {
            foreach($page->translatable as $column)
            {
                $page->{$column}=$page->noFallbackLocale()->getAttributeTranslated($column, $this->activeLocale);

                if(empty($page->{$column}))
                    unset($page->{$column});
            }

            if(!empty($page->children))
            {
                foreach($page->children as $child)
                {
                    foreach($child->translatable as $column)
                    {
                        $child->{$column}=$child->noFallbackLocale()->getAttributeTranslated($column, $this->activeLocale);
        
                        if(empty($child->{$column}))
                            unset($child->{$column});
                    }

                    $child->depth=new \stdClass();
                }
                
                foreach($page->children as $k=>$one)
                {
                    $one->url=$one->pageUrlLocalized($this->activeLocale, $this->isDefaultLocale);
                    $page->children[$k]=(object) $one->toArray();
                }
            }

            $page->depth=new \stdClass();
            $page->url=$page->pageUrlLocalized($this->activeLocale, $this->isDefaultLocale);

            return !empty($page->title);
        });

        $menu = $this->parseMenu($menu);

        foreach($menu as $k=>$one)
            $menu[$k] = (object) $one->toArray();
            
        return $menu;                 
    }
    
    private function parseMenu($menu, $depthStart=false)
    {
        foreach($menu as $k=>$item)
        {
            if($menu[$k]->depth)
                $menu[$k]->depth=$item->nest_depth+1+($depthStart ? -$depthStart : 0);

            if(!empty($item->children))
                $this->parseMenu($item->children, $depthStart);
        }

        return $menu;
    }

    private function activeMenuCheck(&$menu)
    {
        $path=Request::path()!='/' ? Request::path() : '';

        foreach ($menu as $k=>$item)
        {            
            if (ltrim($item->url, '/')==$path || $this->checkMenuWithParam($path, $item->url))
            {
                $menu[$k]->active=true;
                return true;
            }

            if(!empty($item->children) && $this->activeMenuCheck($item->children))
            {
                $menu[$k]->active = true;

                return true;
            }
        }

        return false;
    }

    private function checkMenuWithParam($path, $url)
    {
        if (!str_contains($url, ':'))
            return false;

        $pathParams = explode('/', $path);
        $urlParams = explode('/', ltrim($url, '/'));
        $flag = false;

        for ($i = 0; $i < sizeof($pathParams); $i++) {
            if (($pathParams[$i] == $urlParams[$i]) && ($i < (sizeof($pathParams) - 1))) {
                $flag = true;
            }
        }

        return $flag;
    }

    public function renderMenu($partial, $params)
    {
        return $this->renderPartial($partial, $params);
    }
}
