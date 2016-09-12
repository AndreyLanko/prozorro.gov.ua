<?php namespace Perevorot\Prozorro\Models;

use Model;

/**
 * Model
 */
class Vacancy extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var array
     */
    public $rules = [
        'title' => 'required',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'perevorot_prozorro_vacancies';
}