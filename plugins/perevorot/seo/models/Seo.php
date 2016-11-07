<?php

namespace Perevorot\Seo\Models;

use Model;
use Perevorot\Seo\Traits\SeoMutators;
use Perevorot\Seo\Traits\SeoTrait;

/**
 * Model
 */
class Seo extends Model
{
    use SeoTrait, SeoMutators;
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
    public $table = 'perevorot_seo_seo';
}
