<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'user_id',
        'roleID',
        'fullName',
        'fName',
        'lName',
        'nationalCode',
        'phoneNumber',
        'birthDay',
        'gender',
        'passCode',
        'marage',
        'fatherHusbandName',
        'profilePhoto',
        'fatherHusbndphoneNumber',
        'gayemjob',
        'postalCode',
        'insuranceSitu',
        'mainPhoneNumber',
        'askForSallery',
    ];
}
