<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'mediaId',
        'userId',
        'userprofileId',
        'classId',
        'turorId',
        'gname',
    ];
    protected $table = 'galleries';

}
