<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    // change default table name
    protected $table = 'country';

    public function orders()
    {
        return $this->hasMany('App\Order');
    }
}
