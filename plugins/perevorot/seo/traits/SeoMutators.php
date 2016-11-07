<?php

namespace Perevorot\Seo\Traits;

use Perevorot\Seo\Models\Seo;
use Perevorot\Seo\Models\Settings;

trait SeoMutators
{
    public function setUrlMaskAttribute($value)
    {
        $this->attributes['url_mask'] = '/'.trim(trim($value, '/'));
    }
}
