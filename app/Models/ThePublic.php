<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ThePublic extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'welcome_text', 
        'employment_text', 
        'employment_character',
        'advertisement_right_banner',
        'gallery_character',
        'advertisement_left_banner',
        'gallery_pic',
        'advertisement_banner',
        'welcome_character',
    ];
}
