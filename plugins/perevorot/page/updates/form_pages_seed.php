<?php

namespace Perevorot\Page\Updates;

use October\Rain\Database\Updates\Seeder;
use Perevorot\Page\Models\Page;

class FormPagesSeed extends Seeder
{
    public function run()
    {
        /*
        $pages = include(__DIR__ . "/pages/form_pages.php");

        foreach ($pages as $page) {
            $pageModel = new Page();

            foreach ($page as $column => $value) {
                $pageModel->{$column} = $value;
            }

            $pageModel->save();
        }
        */
    }
}