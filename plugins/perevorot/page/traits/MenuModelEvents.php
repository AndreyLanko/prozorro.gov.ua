<?php namespace Perevorot\Page\Traits;

use Perevorot\Page\Models\Page;
use DB;

trait MenuModelEvents
{
    public function beforeDelete()
    {
        $page=new Page();

        DB::select('DELETE FROM '.$page->table.' WHERE menu_id = ?', [
            $this->id
        ]);
    }
}
