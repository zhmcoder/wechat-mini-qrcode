<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SmallRuralDog\Admin\Traits\ModelTree;


class Category extends Model
{
    use SoftDeletes,ModelTree;

    protected $guarded = [];

    protected $table = 'admin_category';

    protected $hidden = ['created_at', 'updated_at'];


    public function children()
    {
        return $this->hasMany(get_class($this), 'parent_id')->orderBy('order');
    }
}
