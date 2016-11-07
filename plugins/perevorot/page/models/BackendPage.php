<?php namespace Perevorot\Page\Models;

use Model;
use October\Rain\Database\Traits\NestedTree;

/**
 * MenuPage Model
 */
class BackendPage extends Model
{
    use NestedTree;
    use \Perevorot\Page\Traits\PageByMenuTrait;
    use \Perevorot\Page\Traits\PageModelMutators;

    const PAGE_TYPE_STATIC=1;
    const PAGE_TYPE_CMS=2;
    const PAGE_TYPE_ALIAS=3;
    const PAGE_TYPE_EXTERNAL=4;

    /**
     * @var array implemented traits.
     */
    public $implement = [
        '@RainLab.Translate.Behaviors.TranslatableModel'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'perevorot_page_page';

    /**
     * @var array Translatable fields
     */
    public $translatable = [
        'title',
        'content',
        'meta_title',
        'meta_description',
        'header_title',
    ];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'title',
        'content',
        'meta_title',
        'meta_description',
        'parent_id'
    ];

    /**
     * @var array belongsTo field
     */
    public $belongsTo = [
        'menu' => ['Perevorot\Page\Models\Menu', 'key'=>'menu_id'],
        'alias_page' => ['Perevorot\Page\Models\Page', 'key'=>'alias_page_id'],
        'cms_page' => ['Perevorot\Page\Models\Page', 'key'=>'cms_page_id'],
    ];
}
