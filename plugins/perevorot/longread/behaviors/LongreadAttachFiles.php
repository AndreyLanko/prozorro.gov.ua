<?php namespace Perevorot\Longread\Behaviors;

use Perevorot\Longread\Classes\AttachFileRelationArray;
use System\Classes\ModelBehavior;

class LongreadAttachFiles extends ModelBehavior
{
    public function __construct($model)
    {
        parent::__construct($model);

        $defaultAttachMany=[
            'System\Models\File',
            'order'=>'sort_order'
        ];

        $defaultAttachOne='System\Models\File';

        $model->attachMany=new AttachFileRelationArray(!empty($model->attachMany) ? $model->attachMany : [], $defaultAttachMany, 'many');
        $model->attachOne=new AttachFileRelationArray(!empty($model->attachOne) ? $model->attachOne : [], $defaultAttachOne, 'one');
    }
}
