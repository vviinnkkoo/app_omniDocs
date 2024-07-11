<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KprItemList extends Model
{
    use HasFactory;

    // change default table name
    protected $table = 'kpr_item_list';
}
