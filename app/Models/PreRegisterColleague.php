<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreRegisterColleague extends Model
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
        'marage',
        'fatherHusbandName',
        'profilePhoto',
        'fatherHusbndphoneNumber',
        'gayemjob',
        'postalCode',
        'insuranceSitu', 
        'mainPhoneNumber', 
        'askForSallery', 
        'enSkills', 
        'shabaNamber', 
        'creditCard', 
        'passcode', 
    ];
}
