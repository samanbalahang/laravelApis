<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factor extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'user_profile_id',
        'tuition_id',
        'financial_id',
        'salary_id',
        'factorName',
        'factorDesc',
        'basePrice',
        'basePriceDesc',
        'payPrice',
        'payPriceDesc',
        'situtation',
        'thedate',
    ];

}
