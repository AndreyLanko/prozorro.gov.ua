<?php namespace Perevorot\Page\Models;

use Model;
use Lang;

/**
 * Page Model
 */
class Page extends TreeModel
{
    use \Perevorot\Longread\Traits\LongreadMutators;

    use \Perevorot\Page\Traits\PageModelMutators;
    use \Perevorot\Page\Traits\PageModelEvents;
    use \Perevorot\Page\Traits\PageModelOptions;
    use \Perevorot\Page\Traits\CustomValidationMessages;

    use \October\Rain\Database\Traits\Validation;

    const PAGE_TYPE_STATIC=1;
    const PAGE_TYPE_CMS=2;
    const PAGE_TYPE_ALIAS=3;
    const PAGE_TYPE_EXTERNAL=4;
    const PAGES_CACHE_KEY='perevorot_backend_menu_pages';

    /**
     * @var array implemented traits.
     */
    public $implement = [
        '@RainLab.Translate.Behaviors.TranslatableModel',
        '@Perevorot.Longread.Behaviors.LongreadAttachFiles',
    ];

    public $jsonable = [
        'header_advantages',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'perevorot_page_page';

    /**
     * @var array
     */
    public $rules = [
        'title'=>'required',
        'type'=>'required',
        'menu'=>'required',
        'url'=>'required_if:type,'.self::PAGE_TYPE_STATIC.'|unique:perevorot_page_page|regex:/^[\/A-Za-z0-9\-_\:    ]+$/',
        'alias_page_id'=>'required_if:type,'.self::PAGE_TYPE_ALIAS,
        'cms_page_id'=>'required_if:type,'.self::PAGE_TYPE_CMS,
        'url_external'=>'required_if:type,'.self::PAGE_TYPE_EXTERNAL.'|url',
    ];

    /**
     * @var array Validation custom messages
     */
    public $customMessages = [];

    /**
     * @var array Translatable fields
     */
    public $translatable = [
        'title',
        //'content',
        'meta_title',
        'meta_description',
        'longread',
        'header_title'
    ];

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = true;

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'title',
        //'content',
        'meta_title',
        'meta_description',
        'parent_id',
        'header_image_position'
    ];

    /**
     * @var array belongsTo field
     */
    public $belongsTo = [
        'menu' => [
            'Perevorot\Page\Models\Menu',
            'key'=>'menu_id'
        ],
        'alias_page' => [
            'Perevorot\Page\Models\Page',
            'key'=>'alias_page_id'
        ],
        'cms_page' => [
            'Perevorot\Page\Models\Page',
            'key'=>'cms_page_id'
        ],
    ];

    public $hasMany = [
        'pages' => [
            'Perevorot\Page\Models\Page',
            'key'=>'parent_id'
        ]
    ];

    public $attachOne = [
    ];

    public function __construct()
    {
        parent::__construct();

        $this->noFallbackLocale();
    }

    public function scopeVisible($query)
    {
        $query->where('is_hidden', '=', 0);
    }

    public function scopeEnabled($query, $menu_id)
    {
        $query->where('menu_id', '=', $menu_id);
        $query->where('is_hidden', '=', false);
        $query->where('is_disabled', '=', false);

        $query->orderBy('nest_left', 'ASC');
    }

    public function isActive()
    {

    }

    public function getTitleAttribute()
    {
        return $this->noFallbackLocale()->getAttributeTranslated('title');
    }
}
