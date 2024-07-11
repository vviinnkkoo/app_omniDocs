<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    use HasFactory;

    // change default table name
    protected $table = 'payment_type';

    public function orders()
    {
        return $this->hasMany('App\Order');
    }
}
