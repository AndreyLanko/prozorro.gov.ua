<?php

namespace Perevorot\Prozorro\Models;

use Model;

/**
 * Model
 */
class Material extends Model
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
    public $table = 'perevorot_prozorro_materials';

    /**
     * @var array
     */
    public $attachOne = [
        'image' => [
            'System\Models\File',
        ],
    ];

    public $belongsTo = [
        'author' => [
            'Perevorot\Prozorro\Models\Author',
        ],
        'group' => [
            'Perevorot\Prozorro\Models\Group',
        ],
    ];

    /**
     * @return array
     */
    public function getAuthorOptions()
    {
        $authors = Author::get();
        $options = [];

        foreach ($authors as $author) {
            $options[$author->id] = $author->fio;
        }

        return $options;
    }

    /**
     * @return array
     */
    public function getGroupOptions()
    {
        $groups = Group::where('is_enabled', true)->get();
        $options = [];

        foreach ($groups as $group) {
            $options[$group->id] = $group->title;
        }

        return $options;
    }
}
