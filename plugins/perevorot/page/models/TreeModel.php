<?php

namespace Perevorot\Page\Models;

use Model;
use October\Rain\Database\Traits\NestedTree;

abstract class TreeModel extends Model
{
    use NestedTree {
        scopeParents as scopeParentsTrait;
    }

    /**
     * Returns a prepared query with all parents up the tree.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeParents($query, $includeSelf = false)
    {
        $query = $this->scopeParentsTrait($query, $includeSelf);

        return $query->where('menu_id', $this->menu_id);
    }
}
