<?php namespace Perevorot\Longread;

use System\Classes\PluginBase;

/**
 * Plugin Information File
 */
class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'Perevorot\Longread\Components\Longread' => 'longread',
        ];
    }

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'longread',
            'description' => '',
            'author'      => 'perevorot.com',
            'icon'        => 'icon-film'
        ];
    }

    /**
     * Registers back-end form widget items for this plugin.
     *
     * @return array
     */
    public function registerFormWidgets()
    {
        return [
            'Perevorot\Longread\FormWidgets\Longread' => [
                'label' => 'Longread',
                'code'  => 'longread',
            ],
        ];
    }

}
