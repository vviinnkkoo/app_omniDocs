<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KprItemList extends Model
{
    use HasFactory;

    protected $table = 'kpr_item_list';
    protected $fillable = ['receipt_id', 'kpr_id'];

    public function receipt()
    {
        return $this->belongsTo(Receipt::class, 'receipt_id');
    }
}