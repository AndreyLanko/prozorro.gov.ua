<?php namespace Perevorot\Longread\Components;

use Perevorot\Page\Models\Page;
use Cms\Classes\ComponentBase;
use Request;
use Cache;

class Longread extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'perevorot.page::components.longread.component.name',
            'description' => 'perevorot.page::components.longread.component.description',
            'icon'=>'icon-files-o',
        ];
    }

    public function onRender()
    {
        $page=Page::where('url', '=', $this->page->url)->first();

        return $page->longread;
    }
}
