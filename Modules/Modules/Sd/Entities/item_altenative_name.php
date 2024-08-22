<?php

namespace Modules\Sd\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class item_altenative_name extends Model
{
    use HasFactory;

    protected $table = "item_altenative_names";
    protected $primaryKey = "item_altenative_name_id";

    protected $fillable = [
        'item_altenative_name',

    ];
}
