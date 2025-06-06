<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = [
        'name'
    ];

    public function orders()
    {
        return $this->hasMany('App\OrderItemList');
    }
}
