<?php

namespace Perevorot\Prozorro\Models;

use Model;

/**
 * Model
 */
class Author extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var array
     */
    public $rules = [
        'fio' => 'required',
        'slug' => 'required|unique:perevorot_prozorro_authors',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'perevorot_prozorro_authors';
}
