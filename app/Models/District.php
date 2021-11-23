<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SmallRuralDog\Admin\Traits\ModelTree;

class District extends Model
{
    use SoftDeletes, ModelTree;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}
