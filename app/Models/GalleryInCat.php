<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GalleryInCat extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'gallery_id',
        'gallery_cat_id',
    ];
}
