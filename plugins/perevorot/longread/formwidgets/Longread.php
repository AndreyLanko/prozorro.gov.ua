<?php namespace Perevorot\Longread\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Perevorot\Page\Models\Page;
use RainLab\Translate\Models\Locale;
use ApplicationException;
use System\Models\File;
use SystemException;
use Event;

/**
 * Longread Form Widget
 */
class Longread extends FormWidgetBase
{
    use \Perevorot\Longread\Traits\LongreadBlocks;

    const INDEX_PREFIX = '___index_';
    const ALIAS_PREFIX = '___alias_';

    /**
     * @var array Form field configuration
     */
    public $form;

    /**
     * {@inheritDoc}
     */
    protected $defaultAlias = 'longread';

    /**
     * @var int Count of longread items.
     */
    protected $indexCount = 0;

    /**
     * @var array Collection of form widgets.
     */
    protected $formWidgets = [];

    /**
     * @var array Collection of available languages
     */
    protected $availableLanguages = [];

     /**
      * @var bool Stops nested longreads populating from previous sibling.
      */
    protected static $onAddItemCalled = false;

    protected $blocks = [];

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        Event::fire('longread.blocks.get', [$this], true);

        uasort($this->longreadBlocks, function($a, $b) {
            $a=!empty($a['order']) ? $a['order'] : 0;
            $b=!empty($b['order']) ? $b['order'] : 0;

            return $a-$b;
        });

        if(empty($this->longreadBlocks))
            throw new SystemException(sprintf('Longread blocks are not defined', $block));

        $this->fillFromConfig([
            'blocks',
        ]);

        if(empty($this->blocks))
            $this->blocks=array_keys($this->longreadBlocks);

        $blocks=[];

        foreach($this->blocks as $block)
        {
            if(empty($this->longreadBlocks[$block]))
                throw new SystemException(sprintf('Longread block `%s` from fields.yaml not registered', $block));

            $blocks[$block]=$this->longreadBlocks[$block];
        }

        $this->longreadBlocks=$blocks;

        if (!self::$onAddItemCalled) {
            $this->processExistingItems();
        }

        $this->availableLanguages = $this->getAvailableLanguages();
    }

    /**
     * @return mixed
     */
    public function getAvailableLanguages()
    {
        return Locale::isEnabled()->get();
    }

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        $this->prepareVars();

        return $this->makePartial('longread');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        $this->vars['indexName'] = self::INDEX_PREFIX.$this->formField->getName(false).'[]';
        $this->vars['aliasName'] = self::ALIAS_PREFIX.$this->formField->getName(false).'[]';
        $this->vars['fieldName'] = $this->formField->getName(false);
        $this->vars['aliasPrefix'] = self::ALIAS_PREFIX;
        $this->vars['formWidgets'] = $this->formWidgets;
        $this->vars['blocks'] = $this->longreadBlocks;
        $this->vars['blocksGroups'] = $this->longreadBlocksGroups;
        $this->vars['availableLanguages'] = $this->availableLanguages;
        $this->vars['currentLang'] = substr($this->formField->getName(false), -2);
    }

    /**
     * {@inheritDoc}
     */
    protected function loadAssets()
    {
        $this->addCss('css/longread.css', 'core');
        $this->addJs('js/longread.js', 'core');
        $this->addJs('js/jquery.sticky.js', 'core');
    }

    /**
     * {@inheritDoc}
     */
    public function getSaveValue($value)
    {
        $savedValues=(array) $value;

        $aliases=post(self::ALIAS_PREFIX.$this->formField->getName(false));
        $blocks=post(self::INDEX_PREFIX.$this->formField->getName(false));

        if(!$aliases)
            return '';

        $parsedValues=[];

        foreach($aliases as $index=>$alias)
        {
            $value=[];
            $files=[];

            $blockIndex=$blocks[$index];
            $savedValue=!empty($savedValues[$blockIndex]) ? $savedValues[$blockIndex] : [];

            foreach($this->longreadBlocks[$alias]['fields'] as $fieldAlias=>$field)
            {
                $value[$fieldAlias]=!empty($savedValue[$fieldAlias]) ? $savedValue[$fieldAlias] : '';

                if(!empty($field['valueFrom']))
                {
                    $valueFrom=explode('_', $field['valueFrom']);
                    array_pop($valueFrom);
                    $valueFrom[]=$blockIndex;

                    $files[$fieldAlias]=implode('_', $valueFrom);
                }
            }

            array_push($parsedValues, (object) [
                'alias'=>$alias,
                'value'=>$value,
                'index'=>$blockIndex,
                'files'=>$files
            ]);
        }

        return !empty($parsedValues) ? json_encode($parsedValues, JSON_UNESCAPED_UNICODE) : '';
    }

    protected function processExistingItems()
    {
        $values=$this->getLoadValue() ? : "[]";
        $values=json_decode($values, true);
//dd(self::ALIAS_PREFIX.$this->formField->getName(false));
        $itemIndexes=post(self::INDEX_PREFIX.$this->formField->getName(false), array_pluck($values, 'index'));
        $itemAliases=post(self::ALIAS_PREFIX.$this->formField->getName(false), array_pluck($values, 'alias'));
//dd($itemAliases);
        if (!is_array($itemIndexes))
            return;

        foreach($itemIndexes as $index=>$itemIndex)
        {
            $value=array_first($values, function($key, $value) use($itemIndex){
                return $value['index']===$itemIndex;
            });

            $value=$value && array_key_exists('value', $value) ? $value['value'] : [];

            $this->makeItemFormWidget($itemIndex, $itemAliases[$index], $value);
            $this->indexCount=max((int) $itemIndex, $this->indexCount);
        }
    }

    protected function makeItemFormWidget($index = 0, $alias, $value=[])
    {
        if (empty($this->longreadBlocks[$alias]))
            throw new SystemException(sprintf('Longread block `%s` not registered', $alias));

        if (empty($this->longreadBlocks[$alias]['fields']))
            throw new SystemException(sprintf('Longread block `%s` not not configured properly: use `fields` for defining blocks fields', $alias));

        $this->parseFileUploadData($index);

        $config = $this->makeConfig($this->longreadBlocks[$alias]);

        $config->model=$this->model;
        $config->data=$value;
        $config->alias=$this->alias.'Form'.$index;
        $config->arrayName=$this->formField->getName().'['.$index.']';
        $config->aliasValue=$alias;

        $widget=$this->makeWidget('Backend\Widgets\Form', $config);

        $widget->bindToController();
        $widget->vars['aliasValue']=$alias;

        return $this->formWidgets[$index]=$widget;
    }

    /**
     * @param $index
     */
    public function parseFileUploadData($index)
    {
        foreach($this->longreadBlocks as $alias=>$block)
        {
            foreach($block['fields'] as $field=>$fields)
            {
                if(!empty($fields['type']) && $fields['type'] == 'fileupload')
                {
                    $this->longreadBlocks[$alias]['fields'][$field]['valueFrom'] = $this->getFileFieldName($field, $alias, $index);
                }
            }
        }
    }

    /**
     * @param $field
     * @param $alias
     * @param $index
     * @return string
     */
    private function getFileFieldName($field, $alias, $index)
    {
        return sprintf(
            '__longread_%s_%s_%s_%s_%s',
            (ends_with($field, 's')?'many':'one'),
            $this->fieldName,
            $alias,
            $field,
            !self::$onAddItemCalled ? $index : ''
        );
    }

    /**
     * @return array
     */
    public function onAddItem()
    {
        $alias=post(self::ALIAS_PREFIX);

        if(empty($alias))
            throw new SystemException('Please select block to add');

        self::$onAddItemCalled = true;

        $this->indexCount++;

        $this->prepareVars();

        $this->vars['widget'] = $this->makeItemFormWidget($this->indexCount, $alias);
        $this->vars['indexValue'] = $this->indexCount;
        $this->vars['aliasValue'] = $alias;

        $itemContainer = '@#'.$this->getId('items');

        return [$itemContainer => $this->makePartial('longread_item')];
    }

    /**
     * @return array
     */
    public function onCopyLongread()
    {
        //Get from field name and model
        $fromFieldName = 'longread_' . post('fromFieldName');
        $model = $this->model;

        //Parse content
        $fromLongreadContent = json_decode($model->{$fromFieldName}, 1);
        $toLongreadContent = (array) json_decode($model->{$this->fieldName}, 1);

        $offset = sizeof($toLongreadContent);

        if(empty($fromLongreadContent))
            throw new ApplicationException(sprintf('Лонгрид `%s` не содержит блоков', $fromFieldName));

        array_walk($fromLongreadContent, function (&$value, $key) use ($offset) {
            $value['index'] = $offset + $key;
            $value['widget'] = $this->makeItemFormWidget($offset + $key, $value['alias'], (object) $value['value']);
        });

        $this->copyImages($fromLongreadContent);

        array_walk($fromLongreadContent, function (&$value, $key) {
            $files = [];
            $arrayOfFiles = (array) $value['files'];

            foreach ($arrayOfFiles as $key => $file) {
                $files[$key] = $this->getFileFieldName($key, $value['alias'], $value['index']);
            }

            $value['files'] = $files;
        });

        $this->prepareVars();
        $this->vars['blocks'] = $fromLongreadContent;

        $itemContainer = '@#'.$this->getId('items');

        return [$itemContainer => $this->makePartial('longread_items')];
    }

    /**
     * @param $toLongreadContent
     */
    private function copyImages($toLongreadContent)
    {
        foreach ($toLongreadContent as $block) {
            if (!isset($block['files'])) {
                continue;
            }

            $files = $block['files'];

            foreach ($files as $key => $file) {
                $fromFile = File::where('field', $file)->first();

                if (!$fromFile) {
                    continue;
                }

                $newFile = $fromFile->replicate();
                $newFile->field = $this->getFileFieldName($key, $block['alias'], $block['index']);
                $newFile->disk_name = null;
                $newFile->fromFile($fromFile->getLocalPath());
                $newFile->save();
            }
        }
    }

    public function onRemoveItem()
    {
        $this->removeFiles();
    }

    /**
     * Remove files, that attached to block
     */
    private function removeFiles()
    {
        // Useful for deleting relations
        $blocks = (array) json_decode($this->model->{$this->fieldName});

        $block = array_first($blocks, function ($key, $value) {
            return $value->index == post('index');
        });

        if (!isset($block->files)) {
            return;
        }

        $files = (array) $block->files;

        foreach ($files as $item) {
            $file = File::where('field', $item)->first();

            if (!$file) {
                continue;
            }

            $file->delete();
        }
    }
}
