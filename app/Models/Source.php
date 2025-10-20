<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSearch;

class Source extends Model
{
    use HasSearch;
    
    protected $fillable = [
        'name'
    ];
    
    /*
    |--------------------------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------------------------
    */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
