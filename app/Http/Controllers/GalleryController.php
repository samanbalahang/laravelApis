<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GalleryCat;
use App\Models\GalleryInCat;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\ExtrasController;
use PDO;

class GalleryController extends Controller
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
    public function getconnection(){
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
            $media = Gallery::all();
                if(!$media->isEmpty()){
                    $datajson = json_encode($media, JSON_UNESCAPED_UNICODE);
                    $thedata["data"] = $datajson;
                }else{
                    $thedata["situation"] = "False";
                    $thedata["message"] = "گالری پیدا نشد";
                }
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }
        return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        // اسم روت را گرفته و اگر دسته بندی با آن نام وجود داشت به اپ بر میگردانیم.
        // روت های اخبار و مقالات با کمک دسته بندی پست ها فراخوانی میشوند
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
        if($this->isDbconected($request) == "OK"){
            $newreq = $extrasController->jsonRequestToObj($request);
            if($newreq){
              
                $photoAddress = "media/".date("Y-m-d")."";
               
                $objectOfRequesr =  $newreq;

                
                $newMedia = new Gallery;
                $newMedia->userId             =  (isset($objectOfRequesr->userId))?($objectOfRequesr->userId):0;
                $newMedia->userprofileId      =  (isset($objectOfRequesr->userprofileId))?($objectOfRequesr->userprofileId):0;
                $newMedia->classId            =  (isset($objectOfRequesr->classId))?($objectOfRequesr->classId):0;
                $newMedia->turorId            =  (isset($objectOfRequesr->turorId))?($objectOfRequesr->turorId):0;
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
    public function show(Gallery $gallery)
    {
        $extrasController = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection();
        $galleryId = $gallery->id;
        $thegallery = new Gallery;
        $thisGallery = Gallery::find($gallery->id);
        $galleryInCats = GalleryInCat::where("gallery_id",$galleryId)->get();
        $theGall = $thisGallery->toArray();
        $theGall["category"] = [];
        $theGall["user"] = [];

        if(!$galleryInCats->isEmpty()){
            foreach($galleryInCats as $galleryInCat){
                $catId = $galleryInCat->gallery_cat_id;
                $cats = GalleryCat::find($catId);
                if(isset($cats)){
                    array_push($theGall["category"], $cats);
                    // $thisGallery["cat"] =  $cats ;
                }
                $userID = $galleryInCat->userprofileId;
                if($userID != 0 ){
                    $theuser = UserProfile::find($userID);
                    if(isset($theuser)){
                        array_push($theGall["user"],$theuser);
                    }
                }

            }

            $thedata["situation"] = "OK";
            $thedata["message"] = "یافت شد";
            $thedata["data"] =  $theGall;
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }else{
            $thedata["situation"] = "OK";
            $thedata["message"] = "یافت شد";
            $thedata["data"] = $theGall;
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }
   

        $thedata["situation"] = "OK";
        $thedata["message"] = "با موفقیت در بانک ذخیره شد";
        $thedata["data"] =  $theGall;
        return json_encode($thedata, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallery $gallery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        //
    }
}
