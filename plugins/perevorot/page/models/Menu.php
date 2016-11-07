<?php namespace Perevorot\Page\Models;

use Model;

/**
 * Menu Model
 */
class Menu extends Model
{
    use \Perevorot\Page\Traits\CustomValidationMessages;
    use \Perevorot\Page\Traits\MenuModelEvents;

    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'perevorot_page_menu';

    /*
     * Validation
     */
    public $rules = [
        'title'=>'required',
        'alias'=>'required|unique:perevorot_page_menu|regex:/^[A-Za-z0-9\-_]+$/|min:3',
    ];

    /**
     * @var array Validation custom messages
     */
    public $customMessages = [];

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
    ];

    /**
     * @var array hasMany field
     */
    public $hasMany = [
        'pages' => [
            'Perevorot\Page\Models\Page',
            'scope'=>'visible'
        ]
    ];
}
