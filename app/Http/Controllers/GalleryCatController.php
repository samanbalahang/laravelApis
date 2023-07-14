<?php

namespace App\Http\Controllers;

use App\Models\GalleryCat;
use App\Models\Media;
use App\Models\Gallery;
use App\Models\GalleryInCat;
use App\Models\mediaCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\ExtrasController;

class GalleryCatController extends Controller
{
    // اگر به بانک وصل هستیم
    public function isDbconected($request = "",$uri = ""){
        // آیا دیتابیس وصل است
        $extrasController = new ExtrasController;
        if($extrasController ->isDatabaseConected() == "OK" ){
            return "OK";
        }else{
            return json_encode($extrasController -> checkConnection(), JSON_UNESCAPED_UNICODE);
        }
    }

    // ایجاد پیام نتیجه بررسی اتصال به بانک
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
            $gallerycat = GalleryCat::all();
                if(!$gallerycat->isEmpty()){
                    $datajson = json_encode($gallerycat, JSON_UNESCAPED_UNICODE);
                    $thedata["data"] = $datajson;
                }else{
                    $thedata["situation"] = "False";
                    $thedata["message"] = "گالری پیدا نشد";
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
        if($this->isDbconected($request) == "OK"){
            $newreq = $extrasController->jsonRequestToObj($request);
            if($newreq){             
                $objectOfRequesr =  $newreq;
                $gallerycat = new GalleryCat;
                $catName = $objectOfRequesr->cat_name;
                $galleryId= $objectOfRequesr->gallery_id;
                $gallerycats = $gallerycat::where("galleryCatName", $catName)->first();
                if(!isset($gallerycats)){
                    $gallerycat->galleryCatName      = $catName;
                    if($gallerycat->save()){
                        $gallerycatID =  $gallerycat->id;  
                        $galleryInCat = new  GalleryInCat;
                        $galleryInCats = $galleryInCat::where("gallery_id",$galleryId)->where("gallery_cat_id",$gallerycatID)->first();
                        if(!isset($galleryInCats)){
                            $galleryInCat->gallery_id     = $galleryId;
                            $galleryInCat->gallery_cat_id = $gallerycatID;
                            $galleryInCat->save();
                            $thedata["situation"] = "OK";
                            $thedata["message"] = "با موفقیت در بانک ذخیره شد";
                            $thedata["data"] = $gallerycat;
                            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
                        }else{

                            $thedata["situation"] = "OK";
                            $thedata["message"] = "با موفقیت در بانک ذخیره شد";
                            $thedata["data"] = $gallerycat;
                            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
                        }
                    };
                }else{
                    $gallerycatID = $gallerycats->id;
                    $galleryInCat = new  GalleryInCat;
                    $galleryInCats = $galleryInCat::where("gallery_id",$galleryId)->where("gallery_cat_id",$gallerycatID)->first();
                    if(!isset($galleryInCats)){
                        $galleryInCat->gallery_id     = $galleryId;
                        $galleryInCat->gallery_cat_id = $gallerycatID;
                        $galleryInCat->save();
                        $thedata["situation"] = "OK";
                        $thedata["message"] = "با موفقیت در بانک ذخیره شد";
                        $thedata["data"] = $gallerycat;
                        return json_encode($thedata, JSON_UNESCAPED_UNICODE);
                    }else{

                        $thedata["situation"] = "OK";
                        $thedata["message"] = "با موفقیت در بانک ذخیره شد";
                        $thedata["data"] = $gallerycat;
                        return json_encode($thedata, JSON_UNESCAPED_UNICODE);
                    }
                }
            }
        }else{
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(GalleryCat $galleryCat,$uri)
    {
        $allmedias = [];
        $gCat = GalleryCat::find($uri);
        $medias =  mediaCollection::where("gallery_cat_id",$uri)->get();
        // $galleries = Gallery::where("gcatId",$gCat->id)->get();
        foreach ($medias as $media) {
           if($media->media_id != 0){
                $mediaID = $media->media_id;
                $media = Media::find($mediaID);
                if(isset($media)){
                    array_push($allmedias ,$media);
                }
           }         
        }
        $thedata["situation"] = true;
        $thedata["message"] = "عکس های گالری";
        $thedata["data"] = $allmedias;
        return json_encode( $thedata, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GalleryCat $galleryCat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GalleryCat $galleryCat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,GalleryCat $galleryCat,$id)
    {
        //
        $extrasController = new ExtrasController;
        $thedata = $this->getconnection();
        $unsetMediaReq = $request;
        $thePost= GalleryCat::find($id);
        if($thePost->count() != 0){
            $thePost->delete();
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }

    }
    public function destroymedia(Request $request,GalleryCat $galleryCat,$id)
    {
        //
        $extrasController = new ExtrasController;
        $thedata = $this->getconnection();
        $unsetMediaReq = $request;
        $thePost= mediaCollection::where("media_id",$id)->first();
        if($thePost->count() != 0){
            $thePost->delete();
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }

    }

    /*---------------------------------
    |
    |       MEDIA
    |
     ----------------------------------------*/
    public function media(Request $request){
        $extrasController = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection($request);
        $photo = "";
        if(isset($request->media)){
            $photo = $request->media;
        }
        $photoAddress = "../uploads/collection/";
        $photoname    =  $extrasController->imagefilename();
        $thefile      =  $photoAddress.$photoname.".png";
        $theTextFile  =  $photoAddress.$photoname.".txt"; 
        if($photo != ""){    
            // return $photoAddress;
            $fullAddress           = $photoAddress.$photoname.".png";
            if($myfile = fopen($thefile, "w")){
                $thedata["message"] = "عکس در آدرس".$fullAddress."ذخیره شد"."-";
            }
            fclose($myfile);
            $photo= $extrasController->base64_to_jpeg($photo,$photoAddress,$photoname);
        }
        if($this->isDbconected($request) == "OK"){
            $unsetMediaReq = $request;
            unset($unsetMediaReq["media"]);
            $newreq = $extrasController->jsonRequestToObj($unsetMediaReq);
            if($newreq){
                $photoAddress = "uploads/collection/";
                $objectOfRequesr =  $newreq;
                $themedia = new Media;
                $themedia->media            =  $thefile;
                if($themedia->save()){
                    $mediaId = $themedia->id;
                }else{
                    $mediaId = 0;
                    $thedata["message"] = "ذخیره عکس خطا داد";
                }
                $galleryId = $newreq->gallery_id;
                // $newMedia  = Gallery::find($newreq->gallery_id);
                // if(isset($newMedia)){
                //     $galleryId = $newMedia->id;
                //     $newMedia->cat_id             =  $objectOfRequesr->cat_id;
                //     if($newMedia->save()){
                //         $thedata["situation"] = true;
                //         $thedata["message"] = $thedata["message"]."-&: "."با موفقیت در بانک ذخیره شد";
                //         $thedata["data"] = $newMedia;
                       
                //     };
                // }else{
                //     $galleryId = 0;
                //     $thedata["situation"] = false;
                //     $thedata["message"] = "gallery_id یافت نشد";
                //     // $thedata["data"] = $newMedia;
                // }
                $galleryCatId = $newreq->cat_id;
                $themediaCollection  = new mediaCollection;
                $themediaCollection->media_id       = $mediaId; 
                $themediaCollection->gallery_id     = $galleryId;
                $themediaCollection->gallery_cat_id = $galleryCatId;
                if($themediaCollection->save()){
                    $thedata["situation"] = true;
                    $thedata["message"] = $thedata["message"]."&: ". "تمام اطلاعات در بانک ثبت شد";
                    $thedata["data"] = $newreq ;
                }
                return json_encode($thedata, JSON_UNESCAPED_UNICODE);
            }
        }else{
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }
    }
}
