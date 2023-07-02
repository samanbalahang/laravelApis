<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class postComments extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'post_id',
        'post_uri',
        'user_id',
        'user_profile_id',
        'user_preReg_id',
        'user_preRegCol_id',
        'comment',
        'approved',
    ];
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
