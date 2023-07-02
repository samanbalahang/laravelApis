<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'user_id',
        'user_profile_id',
        'tuition_id',
        'financial_id',
        'factor_id',
        'salary',
        'situtation',
    ];

}
