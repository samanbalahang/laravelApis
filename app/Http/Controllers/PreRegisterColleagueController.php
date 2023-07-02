<?php

namespace App\Http\Controllers;

use App\Models\PreRegisterColleague;
use Illuminate\Http\Request;
use App\Http\Controllers\ExtrasController;

class PreRegisterColleagueController extends Controller
{
    public function isDbconected($request = "",$uri = ""){
        // آیا دیتابیس وصل است
        $extrasController = new ExtrasController;
        if($extrasController ->isDatabaseConected() == "OK" ){
            return "OK";
        }else{
            return json_encode($extrasController -> checkConnection(), JSON_UNESCAPED_UNICODE);
        }
    }
    public function getconnection($request = "",$uri = ""){
        // یک متغیر پایه دیتا براساس اتصال پس میدهد جیسون خواهد شد.
        $extrasController = new ExtrasController;
        $thedata = $extrasController->checkConnection(); 
        return $thedata;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $extrasController = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection();
        if($this->isDbconected() == "OK"){
            $media = PreRegisterColleague::all();
                if(!$media->isEmpty()){
                    $datajson = json_encode($media, JSON_UNESCAPED_UNICODE);
                    $thedata["data"] = $datajson;
                }else{
                    $thedata["situation"] = "False";
                    $thedata["message"] = "کارمند پیدا نشد";
                }
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }
        return json_encode($thedata, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $extrasController = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection($request);
        $photo = "";
        if(isset($request->profilePhoto)){
            $photo = $request->profilePhoto;
        }
        $photoAddress = "../uploads/PreRegisterColleague/";
        $photoname    =  $extrasController->imagefilename();
        $thefile      =  $photoAddress.$photoname.".png";
        $theTextFile  =  $photoAddress.$photoname.".txt";
        if($photo != ""){             
            $fullAddress           = $photoAddress.$photoname.".png";
            if($myfile = fopen($thefile, "w")){
                $thedata["message"] = "عکس ذخیه شد. - ";
            }
            fclose($myfile);
            $photo= $extrasController->base64_to_jpeg($photo,$photoAddress,$photoname);
        }
        if($this->isDbconected($request) == "OK"){
            $unsetMediaReq = $request;
            unset($unsetMediaReq["profilePhoto"]);
            $newreq = $extrasController->jsonRequestToObj($unsetMediaReq);
            if($newreq){
              
            
                $savedAddress = "/uploads/PreRegisterColleague/";
               
                $objectOfRequesr =  $newreq;

                
                $preRegColl = new PreRegisterColleague;
                $preRegColl ->roleID                      =  $objectOfRequesr->roleID;
                $preRegColl ->fullName                    =  $objectOfRequesr->fullName;
                $preRegColl ->fName                       =  $objectOfRequesr->fName;
                $preRegColl ->lName                       =  $objectOfRequesr->lName;
                $preRegColl ->nationalCode                =  $objectOfRequesr->nationalCode;
                $preRegColl ->phoneNumber                 =  $objectOfRequesr->phoneNumber;
                $preRegColl ->birthDay                    =  $objectOfRequesr->birthDay;
                $preRegColl ->marage                      =  $objectOfRequesr->marage;
                $preRegColl ->fatherHusbandName           =  $objectOfRequesr->gayem;
                $preRegColl ->fatherHusbndphoneNumber     =  $objectOfRequesr->gayemphoneNumber;
                $preRegColl ->gayemjob                    =  $objectOfRequesr->gayemjob;
                $preRegColl ->postalCode                  =  $objectOfRequesr->postalCode;
                $preRegColl ->insuranceSitu               =  $objectOfRequesr->insuranceSitu;
                $preRegColl ->mainPhoneNumber             =  $objectOfRequesr->mainPhoneNumber;
                $preRegColl ->askForSallery               =  $objectOfRequesr->askForSallery;
                $preRegColl ->enSkills                    =  $objectOfRequesr->enSkills;
                $preRegColl ->shabaNamber                 =  $objectOfRequesr->shabaNamber;
                $preRegColl ->creditCard                  =  $objectOfRequesr->creditCard;
                $preRegColl ->passcode                    =  $extrasController->createhash($objectOfRequesr->passcode);                
                $preRegColl->profilePhoto                 =  $savedAddress.$photoname.'.jpg';
                if(is_numeric($objectOfRequesr->gender)){
                    $preRegColl ->gender                      =  $objectOfRequesr->gender;
                }else{
                    if($objectOfRequesr->gender == "زن"){
                        $preRegColl ->gender = 0;
                    }else{
                        $preRegColl ->gender = 1;
                    }
                }
                
                // if(isset($objectOfRequesr->profilePhoto)){    
                //     $photo                                =  $extrasController->base64_to_jpeg($objectOfRequesr->profilePhoto,$photoAddress);
                    
                // }
               if($preRegColl->save()){
                    $thedata["situation"] = "OK";
                    $thedata["message"] = $thedata["message"]."با موفقیت در بانک ذخیره شد";
                    $thedata["data"] = $preRegColl;
                    return json_encode($thedata, JSON_UNESCAPED_UNICODE);
                }else{
                    $thedata["situation"] = "False";
                    $thedata["message"] = "خطا در ذخیره کردن";
                    return $thedata;
                }
            }else{
                $thedata["situation"] = "False";
                $thedata["message"] = "newreq خطا";
                return json_encode($thedata, JSON_UNESCAPED_UNICODE);
            }
        }else{
            $thedata["situation"] = "False";
            $thedata["message"] = "isDbconected خطا";
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }
        return json_encode($thedata, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Display the specified resource.
     */
    public function show(PreRegisterColleague $preRegisterColleague)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PreRegisterColleague $preRegisterColleague)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PreRegisterColleague $preRegisterColleague)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PreRegisterColleague $preRegisterColleague)
    {
        //
    }
}
