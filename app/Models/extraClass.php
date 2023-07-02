<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class extraClass extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'class_id',
        'week_day_id',
        'StartDate',
        'EndDate',
        'sliderPhoto',
    ];
}
