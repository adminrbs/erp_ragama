<?php

namespace Modules\Sd\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer_grade extends Model
{
    use HasFactory;
    //protected $table = "customer_grades";
    protected $primaryKey = "customer_grade_id";
    protected $fillable = [
        'grade',
        'status_id',
    ];

}
