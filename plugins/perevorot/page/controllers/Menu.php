<?php namespace Perevorot\Page\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Menu extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController'
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'page'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Perevorot.Page', 'perevorot-page-main', 'perevorot-page-menugroup');
    }
}
