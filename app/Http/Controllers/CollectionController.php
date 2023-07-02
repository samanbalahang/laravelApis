<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\CollectionComment;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ExtrasController;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function isDbconected(){
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

    public function index(Request $request)
    {
      
        $extrasController = new ExtrasController;
        // $thedata = $this->getconnection();
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection();
        if($this->isDbconected() == "OK"){
            $collection = Collection::all();
            $thedata["data"] = $collection;
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }    
        return json_encode($thedata, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        echo "create";
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
        if(isset($request->colectphoto)){
            $photo = $request->colectphoto;
        }
        $photoAddress = "../uploads/collection/";
        $photoname    =  $extrasController->imagefilename();
        $thefile      =  $photoAddress.$photoname.".png";
        $theTextFile  =  $photoAddress.$photoname.".txt"; 
        if($photo != ""){    
            $fullAddress           = $photoAddress.$photoname.".png";
            if($myfile = fopen($thefile, "w")){
                $thedata["message"] = "عکس در آدرس".$fullAddress."ذخیره شد"."-";
            }
            fclose($myfile);
            $photo= $extrasController->base64_to_jpeg($photo,$photoAddress,$photoname);
        }
        // ADD LOGO
        $logo = "";
        if(isset($request->logo)){
            $logo = $request->logo;
        }
        $logoAddress = "../uploads/collection/";
        $logoname    =  $extrasController->imagefilename();
        $thelogo      =  $logoAddress.$logoname.".png";
        if($logo != ""){    
            // $logofullAddress           = $logoAddress.$logoname.".png";
            if($mylogo = fopen($thelogo, "w")){
                $thedata["message"] =$thedata["message"] .  "لوگو ذخیره شد";
            }
            fclose($mylogo);
           $logo= $extrasController->base64_to_jpeg($logo,$logoAddress,$logoname);
        }


        // ENDLOGO




        if($this->isDbconected() == "OK"){
            $unsetMediaReq = $request;
            unset($unsetMediaReq["colectphoto"]);
            unset($unsetMediaReq["logo"]);
            $newreq = $extrasController->jsonRequestToObj($unsetMediaReq);
            if($newreq){
                $savedAddress = "/uploads/collection/";
                $objectOfRequesr =  $newreq;
                $newCollection = new  Collection;
                $newCollection->url          =  $objectOfRequesr->url;
                $newCollection->colectName   =  $objectOfRequesr->colectName;
                $newCollection->logo         =  $savedAddress.$logoname.'.jpg';
                $newCollection->colectphoto  =  $savedAddress.$photoname.'.jpg';
                $newCollection->content      =  $objectOfRequesr->content;
                $newCollection->address      =  $objectOfRequesr->address;
                $newCollection->map          =  $objectOfRequesr->map;
                $newCollection->socialA      =  $objectOfRequesr->socialA;
                $newCollection->socialB      =  $objectOfRequesr->socialB;
                $newCollection->socialC      =  $objectOfRequesr->socialC;
                $newCollection->socialD      =  $objectOfRequesr->socialD;
                $newCollection->gallery_id   =  $objectOfRequesr->gallery_id;

                if($newCollection->save()){
                    $thedata["situation"] = "OK";
                    $thedata["message"] = "با موفقیت در بانک ذخیره شد";
                    $thedata["data"] = $newCollection;
                    return json_encode($thedata, JSON_UNESCAPED_UNICODE);
                }else{
                    $thedata["situation"] = "false";
                    $thedata["message"] = "خطا در ذخیره داده";
                    return json_encode($thedata, JSON_UNESCAPED_UNICODE);
                }
            }else{
                $thedata["situation"] = "false";
                $thedata["message"] = "خطا در شناسایی جیسون دریافتی";
                return json_encode($thedata, JSON_UNESCAPED_UNICODE);
            }
        }else{
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Collection $collection,$url = "")
    {
       $extrasController = new ExtrasController;
       $thedata = $this->getconnection();
        //    var_dump(Collection::where("url",$url)->get());
        //    echo "bbbbaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa <br>";
        //    var_dump(Collection::where("url",$url)->first());
        //    dd();
       $theCollection =Collection::where("url",$url)->firstOrFail();
       $collectionId = $theCollection->id;
       $theComments = new  CollectionComment;
       $allComments = [];
       $comments = $theComments::Where("collection_id",$collectionId)->Where("approved",1)->get();
       if($comments->count() != 0 ){
            foreach ($comments as $comment) {
                $finduser = new UserProfile;
                $userProfileId = $comment->user_profile_id;
                $user = $finduser::find($userProfileId);
                if(isset($user)){
                    $comment["theUserProf"] = $user;
                }else{
                    $comment["theUserProf"] = [];
                }
               
                array_push($allComments,$comment );
            }
       }
        //    $comments = $theCollection->comments;
    //    if($comments->isEmpty()){
    //         $comments = [];
    //    }
    //    return $comments; 
    //    $eport = [$theCollection ,"comments"=>$comments];
       $eport = [$theCollection ,"comments"=>$allComments];
       if($theCollection->count() != 0 ){
            // $thedata["data"] = $theCollection;
            $thedata["data"] = $eport;
            // var_dump($url);
            // var_dump(Collection::where("url",$url)->get());
            // dd($theCollection["url"]);
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
       }else{
            $thedata["situation"] = False;
            $thedata["message"] = "بانک داده خالی است";
            $thedata["data"] = $theCollection;
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
       }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Collection $collection)
    {
        echo "edit";
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Collection $collection)
    {
        echo "update";
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection)
    {
        echo "destroy";
        //
    }
}
