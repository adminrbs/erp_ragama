<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_activity_log extends Model
{
    use HasFactory;
    protected $table = 'user_activity_logs';
    protected $primaryKey = 'user_activity_log_id';

    protected $fillable = ['user_activity_log_id','user_activity_id','user_id','activity_date_time','data','created_at','updated_at'];
}
