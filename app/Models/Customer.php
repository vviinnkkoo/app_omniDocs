<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Specify which attributes are mass-assignable
    protected $fillable = [
        'name', 'oib', 'email', 'phone', 'address', 'house_number', 'city', 'postal', 'country_id'
    ];

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

}
