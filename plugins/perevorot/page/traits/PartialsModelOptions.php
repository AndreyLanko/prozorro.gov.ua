<?php namespace Perevorot\Page\Traits;

use Cms\Classes\Partial as CmsPartial;
use Illuminate\Support\Facades\Lang;

trait PartialsModelOptions
{
    public function getMenuOptions()
    {
        return $this->getPartials();
    }

    public function getSubmenuOptions()
    {
        return $this->getPartials();
    }

    public function getCmsPartialName($page)
    {
        return !empty($page->title) ? $page->title : $page->baseFileName.'.htm';
    }

    private function getPartials()
    {
        $partials=CmsPartial::sortBy('baseFileName');

        $dropdown=[
            '' => Lang::get('perevorot.page::lang.form.page.select_cms_partial_option')
        ];

        foreach ($partials as $value)
        {
            $path = $value->getFullPath();
            $baseFileName = $value->getBaseFileName();
//            if(strpos(':', $value->url)===false && !starts_with($value->baseFileName, 'menu/'))
            $dropdown[$value->baseFileName] = $this->getCmsPartialName($value);
        }

        return $dropdown;
    }
}