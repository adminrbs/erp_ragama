<?php

namespace Modules\Sd\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class supply_group extends Model
{
    use HasFactory;

    protected $table = "supply_groups";
    protected $primaryKey = "supply_group_id";

    protected $fillable = [
        'supply_group',
       
    ];
}
