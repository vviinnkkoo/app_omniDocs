<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasSearch;

class WorkYears extends Model
{
    use HasSearch;
    
    protected $fillable = [
        'year'
    ];
}
