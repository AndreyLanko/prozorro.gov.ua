<?php

namespace Perevorot\Prozorro\Models;

use Model;

/**
 * Model
 */
class Partner extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var array
     */
    public $rules = [];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'perevorot_prozorro_partners';

    /**
     * @var array
     */
    public $attachOne = [
        'logo' => [
            'System\Models\File',
        ],
    ];
}
