<?php namespace Perevorot\Page\Components;

use RainLab\Translate\Models\Locale as LocaleModel;
use RainLab\Translate\Classes\Translator;
use Cms\Classes\ComponentBase;

class LocalePicker extends ComponentBase
{
    use \Perevorot\Page\Components\Traits\LocalePickerUtils;
    use \Perevorot\Page\Components\Traits\LocalePickerAjax;

    private $translator;
    private $locales;
    private $localeCodes;
    private $activeLocale;
    private $langNamespace='perevorot.page::components.locale_picker.';

    public function init()
    {
        $this->translator = Translator::instance();
        $this->activeLocale = $this->translator->getLocale();
        $this->localeCodes = array_keys(LocaleModel::listEnabled());
        $this->locales = $this->parseLocales();
    }

    public function onRun()
    {
        $this->page['activeLocale'] = $this->activeLocale;
        $this->page['locales'] = $this->locales;
        $this->page['partialType'] = '@_'.$this->property('type');
    }

    public function componentDetails()
    {
        return [
            'name' => $this->langNamespace.'component.name',
            'description' => $this->langNamespace.'component.description',
            'icon'=>'icon-files-o',
        ];
    }

    public function defineProperties()
    {
        return [
            'type' => [
                'title' => $this->langNamespace.'properties.view_type.name',
                'description' => $this->langNamespace.'properties.view_type.description',
                'default' => 'inline',
                'type' => 'dropdown',
                'options' => [
                    'select' => $this->langNamespace.'properties.view_type.options.select',
                    'inline' => $this->langNamespace.'properties.view_type.options.inline',
                ]
            ],
            'is_current_hidden' => [
                'title' => $this->langNamespace.'properties.is_current_hidden.name',
                'description' => $this->langNamespace.'properties.is_current_hidden.description',
                'default' => false,
                'type' => 'checkbox'
            ],
            'is_switch_same_url' => [
                'title' => $this->langNamespace.'properties.is_switch_same_url.name',
                'description' => $this->langNamespace.'properties.is_switch_same_url.description',
                'default' => 'index',
                'type' => 'dropdown',
                'options' => [
                    'index'=>$this->langNamespace.'properties.is_switch_same_url.options.index',
                    'current'=>$this->langNamespace.'properties.is_switch_same_url.options.current',
                ]
            ]
        ];
    }
}
