<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CollectionComment extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'user_id',
        'user_profile_id',
        'pre_reg_id',
        'pre_reg_collige_id',
        'comment',
        'approved',
    ];
    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class,'collection_id');
    }
}
