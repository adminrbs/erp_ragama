<?php

namespace Modules\Sd\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer_group extends Model
{
    use HasFactory;
    protected $table = "customer_groups";
    protected $primaryKey= "customer_group_id";

    protected $fillable = [
        'group',
        'status_id',
    ];




}
