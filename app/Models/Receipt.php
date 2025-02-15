<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    // fillable for checkbox
    protected $fillable = ['is_cancelled'];
    protected $casts = ['is_cancelled' => 'boolean'];
    
    public function setPaidAmountAttribute($value)
    {
        $this->attributes['paid_amount'] = is_null($value) ? null : str_replace(',', '.', $value);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
