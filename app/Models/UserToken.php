<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    protected $fillable = [
        'user_id',
        'api_token',
        'expire_at'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'expire_at'
    ];

    public function user()
    {
        return $this->belongsTo(AdminUser::class, 'user_id', 'id');
    }

}
