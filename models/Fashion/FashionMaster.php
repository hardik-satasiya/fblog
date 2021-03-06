<?php namespace HS\Models\Fashion;

use Model;

class FashionMaster extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    protected $table = 'fashion_master';

    /**
     * Validation rules
     */
    public $rules = [
        'title' => 'required|between:3,64',
        'slug' => 'required|between:3,64',
    ];

    public $attachOne = [
        'feature_image' => 'System\Models\File'
    ];

    public $attachMany = [
        'topic_images' => ['System\Models\File', 'order' => 'sort_order']
    ];


}
