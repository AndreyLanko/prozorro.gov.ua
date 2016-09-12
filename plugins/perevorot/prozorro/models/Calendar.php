<?php namespace Perevorot\Prozorro\Models;

use Model;

/**
 * Model
 */
class Calendar extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $jsonable = [
        'programm',
    ];

    /**
     * @var array
     */
    public $rules = [
        'title' => 'required',
        'slug' => 'required|unique:perevorot_prozorro_calendar',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'perevorot_prozorro_calendar';
}
