<?php namespace Perevorot\Longread\Traits;

use ReflectionClass;
use Yaml;

trait LongreadBlocks
{
    public $longreadBlocks=[];
    public $longreadBlocksGroups=[];

    public function registerBlocks($plugin)
    {
        $reflection = new ReflectionClass(get_class($plugin));

        $dirname=dirname($reflection->getFileName());
        $yamlFilePath = $dirname.'/longread/longread.yaml';

        $pluginLongreadBlocksGroups=[];
        $pluginLongreadBlocks=[];

        if(file_exists($yamlFilePath))
        {
            $pluginLongreadBlocksGroups = Yaml::parse(file_get_contents($yamlFilePath));

            if (!is_array($pluginLongreadBlocksGroups))
                throw new SystemException(sprintf('Invalid format of the longread block configuration file: %s.', $yamlFilePath));
        }

        foreach($pluginLongreadBlocksGroups as $alias=>$group)
        {
            if(!empty($group['blocks']))
                $pluginLongreadBlocks=array_merge($group['blocks'], $pluginLongreadBlocks);
            else
                $pluginLongreadBlocks=array_merge([
                    $alias=>$group
                ], $pluginLongreadBlocks);
        }

        $pluginFolder=last(explode('/', $dirname));

        $pluginLongreadBlocks=array_map(function($block) use ($pluginFolder){
            $block['plugin']=$pluginFolder;

            return $block;
        }, $pluginLongreadBlocks);

        $this->longreadBlocksGroups=array_merge($this->longreadBlocksGroups, $pluginLongreadBlocksGroups);
        $this->longreadBlocks=array_merge($this->longreadBlocks, $pluginLongreadBlocks);
    }
}
