<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'userId',
        'userprofileId',
        'classId',
        'turorId',
        'media',
    ];
}
