<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Classes extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'the_classes';
    protected $fillable = [
        'url',
        'Class_title',
        'className',
        'sliderPhoto',
        'classDescription',
        'content',
        'regStart',
        'regEnd',
        'dateStart',
        'dateEnd',
        'tutor_id',
        'daysInweek',
        'timeOfDay',
        'price',
        'capacity',
        "address",
        "Class_type",
    ];
}
