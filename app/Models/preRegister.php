<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class preRegister extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'roleID',
        'fullName',
        'fName',
        'lName',
        'nationalCode',
        'phoneNumber',
        'birthDay',
        'gender',
        'parentsSituation',
        'passcode', 
        'howKnowUs',
        'profilePhoto',  
    ];
}
