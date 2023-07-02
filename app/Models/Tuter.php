<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tuter extends Model
{
    use HasFactory,SoftDeletes;
        protected $fillable = [
        'user_id',
        'user_profile_id',
        'preRegColleag_id',
    ];
}
