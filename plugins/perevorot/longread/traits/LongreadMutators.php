<?php namespace Perevorot\Longread\Traits;

use Perevorot\Page\Models\Page;
use RainLab\Translate\Classes\Translator;
use Cms\Classes\ComponentManager;
use Cms\Classes\Controller;
use Event;

trait LongreadMutators
{
    use \Perevorot\Longread\Traits\LongreadBlocks;

    private $locale;

    public function getLongreadArrayAttribute()
    {
        if(!$this->locale)
            $this->locale=Translator::instance()->getLocale();

        $longreadColumn='longread_'.$this->locale;

        $longread=json_decode($this->$longreadColumn) ? : [];

        foreach($longread as $k=>$block)
        {
            if(!empty($block->files))
            {
                foreach($block->files as $column=>$valueFrom)
                    $block->value->$column=$this->$valueFrom;
            }

            unset($block->files);
        }

        return $longread;
    }

    public function setLongreadArrayAttribute($value)
    {
        if(!$this->locale)
            $this->locale=Translator::instance()->getLocale();

        $longreadColumn='longread_'.$this->locale;

        $longread = json_encode($value);

        $this->attributes[$longreadColumn] = $longread;
    }

    public function getLongreadAttribute()
    {
        Event::fire('longread.blocks.get', [$this], true);

        $result=[];

        $controller=Controller::getController();
        $page=$controller->getPage();

        $manager=ComponentManager::instance();

        foreach($this->getLongreadArrayAttribute() as $block)
        {
            $plugin=!empty($this->longreadBlocks[$block->alias]['plugin']) ? $this->longreadBlocks[$block->alias]['plugin'] : false;

            if(!$plugin)
                continue;

            if(!array_key_exists($block->alias, $page->components))
            {
                $className=sprintf('Perevorot\%s\Longread\%s', ucfirst($plugin), ucfirst($block->alias));

                $manager->registerComponent($className, $block->alias);
    
                $component = $controller->addComponent($block->alias, $block->alias, [
                    'plugin'=>$plugin,
                    'alias'=>$block->alias,
                ]);

                $component->onRun();
            }

            array_push($result, $controller->renderComponent($block->alias, [
                'value'=>json_encode($block->value, JSON_UNESCAPED_UNICODE),
                'index' => $block->index,
            ]));
        }

        return implode('', array_filter($result));
    }

    /**
     * @param $widgetName
     * @param $attribute
     * @return string
     */
    public function getLongreadParameter($widgetName, $attribute)
    {
        $form = array_first($this->longreadArray, function ($key, $item) use ($widgetName) {
            return $item->alias == $widgetName;
        });

        return (!is_null($form) && !is_null($form->value))?$form->value->{$attribute}:'';
    }

    /**
     * @param $index
     * @param $attribute
     * @return string
     */
    public function getLongreadByIndex($index, $attribute)
    {
        $form = array_first($this->longreadArray, function ($key, $item) use ($index) {
            return $item->index == $index;
        });

        return (!is_null($form) && !is_null($form->value))?$form->value->{$attribute}:'';
    }
}
