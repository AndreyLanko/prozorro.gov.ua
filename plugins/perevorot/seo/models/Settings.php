<?php

namespace Perevorot\Seo\Models;

use October\Rain\Database\Model;
use Perevorot\Seo\Traits\SeoTrait;

class Settings extends Model
{
    use SeoTrait;

    /**
     * @var array
     */
    public $implement = [
        'System.Behaviors.SettingsModel',
        '@RainLab.Translate.Behaviors.TranslatableModel',
    ];

    /**
     * @var array
     */
    public $translatable = [
        'title',
        'description',
        'keywords',
        'additional_tags',
    ];

    /**
     * @var string
     */
    public $settingsCode = 'perevorot_seo_settings';

    /**
     * @var string
     */
    public $settingsFields = 'fields.yaml';

    /**
     * @var array
     */
    public $attachOne = [
        'favicon' => [
            'System\Models\File',
        ]
    ];
}
