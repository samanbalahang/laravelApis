<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


// شهریه
class Tuition extends Model
{
    // شهریه
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'class_id',
        'theDate',
        'year',
        'month',
    ];
}
