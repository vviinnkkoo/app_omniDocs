<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemList extends Model
{
    use HasFactory;

    // Change default table name
    protected $table = 'order_item_list';

    // Fillable for checkbox
    protected $fillable = ['is_done'];
    protected $casts = ['is_done' => 'boolean'];

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = is_null($value) ? null : str_replace(',', '.', $value);
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = is_null($value) ? null : str_replace(',', '.', $value);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
