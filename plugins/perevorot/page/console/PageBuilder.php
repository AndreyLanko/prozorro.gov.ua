<?php namespace Perevorot\Page\Console;

use Illuminate\Console\Command;
use Perevorot\Page\Classes\PageFile;
use Perevorot\Page\Models\Page;

class PageBuilder extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'perevorot:page:build';

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

        foreach ($pages as $page) {
            switch ($page->type) {
                case Page::PAGE_TYPE_STATIC:
                    PageFile::save($page);
                    break;
            }
        }

        $this->output->write('Страницы успешно созданы');
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [];
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