<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class mediaCollection extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'media_id',
        'collection_id',
        'collection_cat_id',
        'user_Id',
        'userprofile_Id',
        'class_Id',
        'turor_Id',
        'gallery_id',
        'gallery_cat_id',
    ];
}
