<?php namespace Perevorot\Page\Traits;

use Lang;

trait CustomValidationMessages
{
    public function __construct()
    {
        parent::__construct();

        foreach($this->rules as $column=>$rule)
        {
            $types=explode('|', $rule);

            foreach($types as $type)
            {
                $one_type=explode(':', $type)[0];
                $this->customMessages[$column.'.'.$one_type]=Lang::get('perevorot.page::lang.validation.'.$column.'_'.$one_type);
            }
        }
    }
}
