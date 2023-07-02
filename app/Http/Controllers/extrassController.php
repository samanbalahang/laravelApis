<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Extras;
use App\Models\extraClass;
use App\Models\ExtrassAgrement;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ExtrasController;

// فوق برنامه
class extrassController extends Controller
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
        $extrasController = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection();
        if($this->isDbconected() == "OK"){
            $extrasses = Extras::all();
            if(!$extrasses->isEmpty()){
                $datajson = json_encode($extrasses, JSON_UNESCAPED_UNICODE);
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
     *      ذخیره داده ها در بانک
     * 
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
                $newExtras = new Extras;
                $newExtras->extraName       =  $objectOfRequesr->extraName;
                $newExtras->extraDesc       =  $objectOfRequesr->extraDesc;
                $newExtras->extraContent    =  $objectOfRequesr->extraContent;
                if($newExtras->save()){
                    $thedata["situation"] = "OK";
                    $thedata["message"] = "با موفقیت در بانک ذخیره شد";
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
