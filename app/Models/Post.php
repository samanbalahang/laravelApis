<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'postTypeId', 
        'collection_id', 
        'uri',
        'title',
        'description',
        'content',
        'photo',
        'galleryId',
    ];
    public function comments(): HasMany
    {
        return $this->hasMany(postComments::class,'post_id', 'id');
    }
}
