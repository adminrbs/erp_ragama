<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class global_setting extends Model
{
    protected $table = 'global_settings';
    protected $primaryKey = 'global_setting_id';
    use HasFactory;
}
