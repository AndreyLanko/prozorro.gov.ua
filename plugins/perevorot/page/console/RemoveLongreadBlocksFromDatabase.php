<?php namespace Perevorot\Page\Console;

use Illuminate\Console\Command;
use Perevorot\Page\Classes\PageFile;
use Perevorot\Page\Models\Page;
use Symfony\Component\Console\Input\InputArgument;

class RemoveLongreadBlocksFromDatabase extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'perevorot:blocks:remove';

    /**
     * @var string The console command description.
     */
    protected $description = 'Build pages';

    /**
     * Execute the console command.
     * @return void
     */
    public function fire()
    {
        $pages = Page::get();
        $removedBlock = $this->argument('block');

        foreach ($pages as $page) {
            $longreadBlocks = $page->longreadArray;

            foreach ($longreadBlocks as $key => $block) {
                if ($block->alias == $removedBlock) {
                    unset($longreadBlocks[$key]);
                }
            }

            $page->longreadArray = $longreadBlocks;
            $page->save();
        }

        $this->info('Блоки успешно удалены');
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['block', InputArgument::REQUIRED, 'Block name']
        ];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

}