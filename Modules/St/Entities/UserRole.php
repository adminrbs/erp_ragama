<?php

namespace Modules\St\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'users_roles';
    protected $primaryKey = 'user_id';
    use HasFactory;
}
