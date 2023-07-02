<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Tuter;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\PreRegisterColleague;
use Illuminate\Http\Request;
use App\Http\Controllers\ExtrasController;

class ClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
    public function index()
    {
        $extrasController = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection();
        if($this->isDbconected() == "OK"){
            $theClass = [];
            $teacherName="";
            $media = Classes::all();
                if(!$media->isEmpty()){
                    $classes["teacherName"]="";
                    foreach ($media  as $classes) {
                        $classes["teacherName"] = "";
                        if($classes->tutor_id != 0){
                            $thetuter = Tuter::find($classes->tutor_id);
                            if($thetuter->user_id != 0){
                                $user_id = $thetuter->user_id;
                                $teacherName = User::find($user_id)->name;
                                $classes["teacherName"] = $teacherName;
                            }
                            if($thetuter->user_profile_id != 0){
                                $profile_id = $thetuter->user_profile_id;
                                $teacherName = UserProfile::find($profile_id)->fname;
                                $classes["teacherName"] = $teacherName;
                            }
                            if($thetuter->preRegColleag_id != 0){
                                $reg_id = $thetuter->preRegColleag_id;
                                $teacher = PreRegisterColleague::find($reg_id);
                                $teacherName =$teacher->fullName." ".$teacher->fName." ".$teacher->lName;
                                $classes["teacherName"] = $teacherName;
                            }
                            array_push($theClass,$classes);
                        }else{
                            array_push($theClass,$classes);
                        }
                    }
                    // $datajson = json_encode($media, JSON_UNESCAPED_UNICODE);
                    $datajson = json_encode($theClass , JSON_UNESCAPED_UNICODE);
                    $thedata["data"] = $datajson;
                }else{
                    $thedata["situation"] = "False";
                    $thedata["message"] = "کلاسی پیدا نشد";
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
        if(isset($request->sliderPhoto)){
            $photo = $request->sliderPhoto;
        }
        $photoAddress = "../uploads/classes/";
        $photoname    =  $extrasController->imagefilename();
        $thefile      =  $photoAddress.$photoname.".png";
        $theTextFile  =  $photoAddress.$photoname.".txt"; 
        if($photo != ""){    
            // return $photoAddress;
           
            $fullAddress           = $photoAddress.$photoname.".png";
            if($myfile = fopen($thefile, "w")){
                $thedata["message"] = "عکس در آدرس".$fullAddress."ذخیره شد"."-";
            }else{
                $thedata["message"] = "عکس ذخیره نشد.";
            }
            // $myfile = fopen($fullAddress , "w") or die("Unable to open file!");
            fclose($myfile);
            $photo= $extrasController->base64_to_jpeg($photo,$photoAddress,$photoname);
        }


        if($this->isDbconected($request) == "OK"){
            $unsetMediaReq = $request;
            unset($unsetMediaReq["sliderPhoto"]);
            $newreq = $extrasController->jsonRequestToObj($unsetMediaReq);
            if($newreq){
              
                $savedAddress = "/uploads/classes/";
               
                $objectOfRequesr =  $newreq;

                
                $newClass = new Classes;
                $newClass->url               =  $objectOfRequesr->url;
                $newClass->Class_title       =  $objectOfRequesr->Class_title;
                $newClass->className         =  $objectOfRequesr->className;
                $newClass->sliderPhoto       =  $savedAddress.$photoname.'.jpg';
                $newClass->classDescription  =  $objectOfRequesr->classDescription;
                $newClass->content           =  $objectOfRequesr->content;
                $newClass->regStart          =  $objectOfRequesr->regStart;
                $newClass->regEnd            =  $objectOfRequesr->regEnd;
                $newClass->dateStart         =  $objectOfRequesr->dateStart;
                $newClass->dateEnd           =  $objectOfRequesr->dateEnd;
                $newClass->tutor_id          =  $objectOfRequesr->tutor_id;
                $newClass->daysInweek        =  $objectOfRequesr->className;
                $newClass->timeOfDay         =  $objectOfRequesr->timeOfDay;
                $newClass->price             =  $objectOfRequesr->price;
                $newClass->capacity          =  $objectOfRequesr->capacity;
                $newClass->address           =  $objectOfRequesr->address;
                $newClass->Class_type        =  $objectOfRequesr->Class_type;
               if($newClass->save()){
                    $thedata["situation"] = "OK";
                    $thedata["message"] = "با موفقیت در بانک ذخیره شد";
                    $thedata["data"] = $newClass;
                    return json_encode($thedata, JSON_UNESCAPED_UNICODE);
                };
            }
        }else{
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Classes $classes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classes $classes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classes $classes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classes $classes)
    {
        //
    }
}
