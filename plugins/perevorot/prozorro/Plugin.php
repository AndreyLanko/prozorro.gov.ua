<?php namespace Perevorot\Prozorro;

use System\Classes\PluginBase;
use Event;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }
    
    public function boot()
    {
        Event::listen('longread.blocks.get', function($longread){
            $longread->registerBlocks($this);
        });
    }
}
