<?php namespace Perevorot\Page\Traits;

use Perevorot\Page\Classes\PageByMenuScope;

trait PageByMenuTrait {

    /**
     * Boot the page by menu trait for a model.
     *
     * @return void
     */
    public static function bootPageByMenuTrait()
    {
        static::addGlobalScope(new PageByMenuScope);
    }

}
