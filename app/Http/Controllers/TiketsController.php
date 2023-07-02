<?php

namespace App\Http\Controllers;

use App\Models\Tikets;
use Illuminate\Http\Request;
use App\Http\Controllers\ExtrasController;

class TiketsController extends Controller
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
            $tikets = Tikets::all();
                if(!$tikets->isEmpty()){
                    $datajson = json_encode($tikets, JSON_UNESCAPED_UNICODE);
                    $thedata["data"] = $datajson;
                }else{
                    $thedata["situation"] = "False";
                    $thedata["message"] = "عکس پیدا نشد";
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
                $photoAddress = "tikets/".date("Y-m-d")."";
                $objectOfRequesr =  $newreq;
                $newTikets = new Tikets;
                $newTikets->userId           =  $objectOfRequesr->userId;
                $newTikets->userprofileId    =  $objectOfRequesr->userprofileId;
                $newTikets->subject          =  $objectOfRequesr->subject;
                $newTikets->tiketContent     =  $objectOfRequesr->tiketContent;
                $newTikets->whoAnswer        =  $objectOfRequesr->whoAnswer;
                if(isset($objectOfRequesr->tiketFile)){    
                    $photo                =  $extrasController->base64_to_jpeg($objectOfRequesr->tiketFile,$photoAddress);
                    $newTikets->tikets       =  $photo;
                }
               if($newTikets->save()){
                    $thedata["situation"] = "OK";
                    $thedata["message"] = "با موفقیت در بانک ذخیره شد";
                    $thedata["data"] = $newTikets;
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
    public function show(Tikets $tikets)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tikets $tikets)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tikets $tikets)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tikets $tikets)
    {
        //
    }
}
