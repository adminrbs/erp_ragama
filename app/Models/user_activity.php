<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_activity extends Model
{
    use HasFactory;
    protected $table = 'user_activities';
    protected $primaryKey = 'user_activity_id';

    protected $fillable = ['user_activity_id','user_activity','model_type','model_id','created_at','updated_at'];
   
}
