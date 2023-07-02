<?php

namespace App\Http\Controllers;

use App\Models\extraClass;
use Illuminate\Http\Request;
use App\Http\Controllers\ExtrasController;
use App\Http\Controllers\extrassController;

class ExtraClassController extends Controller
{
  /**
     * Display a listing of the resource.
     */
    /*-----------------------------------
    |
    |
    |       isDbconected
    |      اگر بانک وصل است OK

    |
    --------------------------------------*/
    public function isDbconected($request = "",$uri = ""){
        $extrasController = new ExtrasController;
        if($extrasController ->isDatabaseConected() == "OK" ){
            return "OK";
        }else{
            return json_encode($extrasController -> checkConnection(), JSON_UNESCAPED_UNICODE);
        }
    }
    /*-----------------------------------
    |
    |
    |       getconnection
    | یک متغیر پایه دیتا براساس اتصال پس میدهد جیسون خواهد شد.
    |
    --------------------------------------*/
    public function getconnection($request= "",$uri = ""){
        // یک متغیر پایه دیتا براساس اتصال پس میدهد جیسون خواهد شد.
        $extrasController = new ExtrasController;
        $thedata = $extrasController->checkConnection(); 
        return $thedata;
    }
    public function index()
    {
        $extrassclass = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection();
        if($this->isDbconected() == "OK"){
            $extrassclass = extraClass::all();
            if(!$extrassclass->isEmpty()){
                $datajson = json_encode($extrassclass, JSON_UNESCAPED_UNICODE);
                $thedata["data"] = $datajson;
            }else{
                $thedata["situation"] = "False";
                $thedata["message"] = "فوق برنامه ایی برای نمایش وجود ندارد";
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
        $photoAddress = "../uploads/extraClass/";
        $photoname    =  $extrasController->imagefilename();
        $thefile      =  $photoAddress.$photoname.".png";
        $theTextFile  =  $photoAddress.$photoname.".txt"; 
        if($photo != ""){          
            $fullAddress           = $photoAddress.$photoname.".png";
            if($myfile = fopen($thefile, "w")){
                $thedata["message"] = "عکس ذخیره شد. - ";
            }else{
                $thedata["message"] = "عکس ذخیره نشد. - ";
            }
            fclose($myfile);
            $photo= $extrasController->base64_to_jpeg($photo,$photoAddress,$photoname);
        }else{
            $thedata["message"] = "عکس دریافت نشد. - ";
        }




        if($this->isDbconected($request) == "OK"){
            $unsetMediaReq = $request;
            unset($unsetMediaReq["sliderPhoto"]);
            $newreq = $extrasController->jsonRequestToObj($unsetMediaReq);
            if($newreq){     
                $savedAddress = "/uploads/extraClass/";
                // $photoAddress = "extraClass/".date("Y-m-d")."";
                
                $objectOfRequesr =  $newreq;              
                $newExtras = new extraClass;
                $newExtras->class_id        =  $objectOfRequesr->class_id;
                $newExtras->week_day_id     =  $objectOfRequesr->week_day_id;
                $newExtras->StartDate       =  $objectOfRequesr->StartDate;
                $newExtras->EndDate         =  $objectOfRequesr->EndDate;
                $newExtras->content         =  $objectOfRequesr->content;
                $newExtras->sliderPhoto     =  $savedAddress.$photoname.'.jpg';
                // if(isset($objectOfRequesr->sliderPhoto)){    
                //     $photo                  =  $extrasController->base64_to_jpeg($objectOfRequesr->sliderPhoto,$photoAddress);
                   
                // }
                if($newExtras->save()){
                    $thedata["situation"] = "OK";
                    $thedata["message"] = $thedata["message"] . "با موفقیت در بانک ذخیره شد";
                    $thedata["data"] = $newExtras;
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
    public function show(extraClass $extraClass)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(extraClass $extraClass)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, extraClass $extraClass)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(extraClass $extraClass)
    {
        //
    }
}
