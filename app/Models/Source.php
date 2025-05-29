<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $fillable = ['name'];
    
    public function orders()
    {
        return $this->hasMany('App\Order');
    }
}
