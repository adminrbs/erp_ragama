<?php

namespace Modules\Sd\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category_level_1 extends Model
{
    use HasFactory;
    protected $table = "item_category_level_1s";
    protected $primaryKey = "item_category_level_1_id";
    protected $fillable = [
        'category_level_1',
        'status_id',
    ];
}
