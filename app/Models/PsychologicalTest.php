<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PsychologicalTest extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'title',
        'status',
        'enter_link',
    ];

}
