<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'url',
        'colectName',
        'logo',
        'colectphoto',
        'content',
        'address',
        'map',
        'socialA',
        'socialB',
        'socialC',
        'socialD',
        'gallery_id',
    ];
    public function comments(): HasMany
    {
        return $this->hasMany(CollectionComment::class,'collection_id');
    }
}
