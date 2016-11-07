<?php namespace Perevorot\Longread\Classes;

use Cms\Classes\ComponentBase;

class LongreadComponentBase extends ComponentBase
{
    public $value;
    public $alias;
    public $plugin;

    public function componentDetails()
    {
        return [];
    }

    public function init()
    {
        $this->alias=$this->property('alias');
        $this->plugin=$this->property('plugin');
        $this->value=json_decode($this->property('value'));
    }

    public function partial($data=[])
    {
        return $this->renderPartial('longread/'.$this->alias, $data);
    }

    public function onRender()
    {
        return $this->partial([
            'data' => json_decode($this->property('value')),
        ]);
    }
}