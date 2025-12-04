<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemList extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'amount',
        'color_id',
        'price',
        'note',
        'discount',
        'is_done',
        'note_on_invoice'
    ];

    protected $casts = [
        'is_done' => 'boolean',
        'note_on_invoice' => 'boolean'
    ];

    /*
    |--------------------------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------------------------
    */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Mutators
    |--------------------------------------------------------------------------------------------
    */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = is_null($value) ? null : str_replace(',', '.', $value);
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = is_null($value) ? null : str_replace(',', '.', $value);
    }

    /*
    |--------------------------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------------------------
    */
    public function getProductNameAttribute()
    {
        return $this->product->name ?? null;
    }

    public function getProductTypeNameAttribute()
    {
        return $this->productType->name ?? null;
    }

    public function getColorNameAttribute()
    {
        return $this->color->name ?? null;
    }

    public function getUnitAttribute()
    {
        return $this->product->unit ?? null;
    }

    public function getFormattedAmountAttribute()
    {
        if (!$this->unit) {
            return number_format($this->amount, 2, '.', ',');
        }

        return $this->unit === 'kom'
            ? number_format($this->amount, 0)
            : number_format($this->amount, 2, '.', ',');
    }
}
