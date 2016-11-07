<?php namespace Perevorot\Seo;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use Perevorot\Seo\Models\Seo;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'Perevorot\Seo\Components\Seo' => 'SeoMeta'
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'SEO',
                'description' => 'Настройки SEO по умолчанию',
                'category'    => 'Seo',
                'icon'        => 'icon-cog',
                'class'       => 'Perevorot\Seo\Models\Settings',
                'order'       => 50,
                'keywords'    => 'seo, settings',
                'permissions' => []
            ]
        ];
    }
}
