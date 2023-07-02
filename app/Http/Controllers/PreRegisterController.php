<?php

namespace App\Http\Controllers;

use App\Models\preRegister;
use App\Models\TheParent;
use Illuminate\Http\Request;
use App\Http\Controllers\ExtrasController;

class PreRegisterController extends Controller
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
            $media = preRegister::all();
                if(!$media->isEmpty()){
                    $datajson = json_encode($media, JSON_UNESCAPED_UNICODE);
                    $thedata["data"] = $datajson;
                }else{
                    $thedata["situation"] = "False";
                    $thedata["message"] = "پیش ثبت نام یافت نشد پیدا نشد";
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
        $photoAddress = "../uploads/PreRegister/";
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
                $savedAddress = "/uploads/PreRegister/";
               
                $objectOfRequesr =  $newreq;

                
                $preRegColl = new preRegister;
                $preRegColl ->fullName                    =  $objectOfRequesr->fullName;
                $preRegColl ->fName                       =  $objectOfRequesr->fName;
                $preRegColl ->lName                       =  $objectOfRequesr->lName;
                $preRegColl ->nationalCode                =  $objectOfRequesr->nationalCode;
                $preRegColl ->phoneNumber                 =  $objectOfRequesr->phoneNumber;
                $preRegColl ->birthDay                    =  $objectOfRequesr->birthDay;
                if(is_numeric($objectOfRequesr->gender)){
                    $preRegColl ->gender                      =  $objectOfRequesr->gender;
                }else{
                    if($objectOfRequesr->gender == "زن"){
                        $preRegColl ->gender = 0;
                    }else{
                        $preRegColl ->gender = 1;
                    }
                }
                $preRegColl ->parentsSituation            =  $objectOfRequesr->parentsSituation;
                $preRegColl->profilePhoto                 =  $savedAddress.$photoname.'.jpg';
                $preRegColl ->passCode                    =  $extrasController->createhash($objectOfRequesr->passCode);
                $preRegColl ->howKnowUs                   =  $objectOfRequesr->howKnowUs;
               if($preRegColl->save()){
                    $father = new TheParent;
                    $father ->preRegistersId               =  $preRegColl->id;
                    $father ->studentsId                   =  $preRegColl->id;
                    $father ->whoIsParent                  =  "پدر";
                    $father ->fname                        =  $objectOfRequesr->fathfname;
                    $father ->lastName                     =  $objectOfRequesr->fathlastName;
                    $father ->education                    =  $objectOfRequesr->fatheducation;
                    $father ->career                       =  $objectOfRequesr->fathcareer;
                    $father ->phoneNumber                  =  $objectOfRequesr->fathphoneNumber;
                    $father->save();

                    $mother = new TheParent;
                    $mother ->preRegistersId               =  $preRegColl->id;
                    $mother ->studentsId                   =  $preRegColl->id;
                    $mother ->whoIsParent                  =  "مادر";
                    $mother ->fname                        =  $objectOfRequesr->mothfname;
                    $mother ->lastName                     =  $objectOfRequesr->mothlastName;
                    $mother ->education                    =  $objectOfRequesr->motheducation;
                    $mother ->career                       =  $objectOfRequesr->mothcareer;
                    $mother ->phoneNumber                  =  $objectOfRequesr->mothphoneNumber;
                    $mother->save();
                    $thedata["situation"] = "OK";
                    $thedata["message"] = "با موفقیت در بانک ذخیره شد";
                    $thedata["data"] = $request->all();
                    return json_encode($thedata, JSON_UNESCAPED_UNICODE);
                }
            }
        }else{
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(preRegister $preRegister)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(preRegister $preRegister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, preRegister $preRegister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(preRegister $preRegister)
    {
        //
    }
}
