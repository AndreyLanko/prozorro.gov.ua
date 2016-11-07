<?php namespace Perevorot\Page\FormWidgets;

use Backend\Classes\Controller;
use Backend\Classes\FormField;
use Backend\Classes\FormWidgetBase;
use Backend\FormWidgets\FileUpload;
use Carbon\Carbon;
use Perevorot\Universalbank\Components\Rates;
use Perevorot\Universalbank\Models\Manager;
use Perevorot\Universalbank\Models\Rate;
use Perevorot\Universalbank\Models\Value;
use Perevorot\Vacancies\Models\Vacancy;

class Handsontable extends FormWidgetBase
{
    public $defaultAlias = 'handsontable';


    /**
     * @var Controller|null
     */
    protected $controller;

    /**
     * @var null
     */
    protected $formField;

    /**
     * @var null
     */
    protected $configuration;

    /**
     * Handsontable constructor.
     * @param Controller $controller
     * @param FormField $formField
     * @param array $configuration
     */
    public function __construct(Controller $controller, FormField $formField, $configuration)
    {
        parent::__construct($controller, $formField, $configuration);
    }

    /**
     * @return mixed
     * @throws \SystemException
     */
    public function render()
    {
        $this->prepareVars();

        return $this->makePartial('default');
    }

    /**
     *
     */
    public function loadAssets()
    {
        $this->addCss('css/handsontable.min.css');
        $this->addJs('js/pikaday.js');
        $this->addJs('js/ZeroClipboard.Core.min.js');
        $this->addJs('js/handsontable.min.js');
        $this->addJs('js/app.js');
    }

    /**
     *
     */
    public function prepareVars()
    {
        $name = $this->formField->getName();

        $this->vars['id'] = ($this->getConfig('id'))?:"handsontable";
        $this->vars['name'] = $name;
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['rows'] = ($this->getConfig('options')['rows'])?json_encode($this->getConfig('options')['rows']):'[]';
        $this->vars['columns'] = ($this->getConfig('options')['columns'])?json_encode($this->getConfig('options')['columns']):'[]';
    }

    /**
     * @param \Backend\Classes\The $value
     * @return \Backend\Classes\The
     */
    public function getSaveValue($value)
    {
        return $value;
    }
}
