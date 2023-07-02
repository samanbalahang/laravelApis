<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\ExtrasController;

class MediaController extends Controller
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
            $media = Media::all();
                if(!$media->isEmpty()){
                    $datajson = json_encode($media, JSON_UNESCAPED_UNICODE);
                    $thedata["data"] = $datajson;
                }else{
                    $thedata["situation"] = "False";
                    $thedata["message"] = "عکس پیدا نشد";
                }
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }
        return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        // اسم روت را گرفته و اگر دسته بندی با آن نام وجود داشت به اپ بر میگردانیم.
        // روت های اخبار و مقالات با کمک دسته بندی پست ها فراخوانی میشوند.
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
         //
         $extrasController = new ExtrasController;
         // چک کردن کانکشن به بانک
         $thedata = $this->getconnection($request);
         if($this->isDbconected($request) == "OK"){
             $newreq = $extrasController->jsonRequestToObj($request);
             if($newreq){
               
                 $photoAddress = "media/".date("Y-m-d")."";
                
                 $objectOfRequesr =  $newreq;
 
                 
                 $newMedia = new Media;
                 $newMedia->userId      =  $objectOfRequesr->userId;
                 $newMedia->userprofileId         =  $objectOfRequesr->userprofileId;
                 $newMedia->classId       =  $objectOfRequesr->classId;
                 $newMedia->turorId =  $objectOfRequesr->turorId;
                 if(isset($objectOfRequesr->media)){    
                     $photo                =  $extrasController->base64_to_jpeg($objectOfRequesr->media,$photoAddress);
                     $newMedia->media       =  $photo;
                 }
                if($newMedia->save()){
                     $thedata["situation"] = "OK";
                     $thedata["message"] = "با موفقیت در بانک ذخیره شد";
                     $thedata["data"] = $newMedia;
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
    public function show(Media $media)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Media $media)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Media $media)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Media $media)
    {
        //
    }
}
