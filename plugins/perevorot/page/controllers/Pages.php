<?php namespace Perevorot\Page\Controllers;

use Backend\Classes\Controller;
use Backend\Widgets\Form;
use BackendMenu;
use Input;
use October\Rain\Translation\Translator;
use Perevorot\Page\Model\Page;
use RainLab\Translate\Models\Locale;

class Pages extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController'
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public $requiredPermissions = [
        'page'
    ];

    public $bodyClass;
    public $menu;

    public function __construct()
    {
        parent::__construct();

        $this->menu=Input::get('menu');

        if($this->menu)
            BackendMenu::setContext('Perevorot.Page', 'perevorot-page-main', 'perevorot-page-menu'.$this->menu);
        else
            BackendMenu::setContext('Perevorot.Page', 'perevorot-page-main');

        if($this->action!='reorder')
            $this->bodyClass='compact-container';
    }

    public function filterFields($fields)
    {
        if ($fields == 'header_advantages') {

        }
    }

    /**
     * @param $form
     */
    public function formExtendFields(Form $form)
    {
        $locales = Locale::isEnabled()->get();
        $fields = [];

        foreach ($locales as $locale) {
            $fields['longread_' . $locale->code] = [
                'type' => 'longread',
                'cssClass' => 'field-slim',
                'stretch' => true,
                'tab' => $locale->name,
            ];
        }

        $form->addSecondaryTabFields($fields);
    }
}
