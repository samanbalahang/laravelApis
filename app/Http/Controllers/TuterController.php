<?php

namespace App\Http\Controllers;

use App\Models\Tuter;
use Illuminate\Http\Request;

class TuterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // آیا بانک داده وصل است
    public function isDbconected(){
        $extrasController = new ExtrasController;
        if($extrasController ->isDatabaseConected() == "OK" ){
            return "OK";
        }else{
            return json_encode($extrasController -> checkConnection(), JSON_UNESCAPED_UNICODE);
        }
    }

    // یک متغیر پایه دیتا براساس اتصال پس میدهد جیسون خواهد شد
    public function getconnection(){
        // یک متغیر پایه دیتا براساس اتصال پس میدهد جیسون خواهد شد.
        $extrasController = new ExtrasController;
        $thedata = $extrasController->checkConnection(); 
        return $thedata;
    }

    public function index()
    {
        $extrasController = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata  = $this->getconnection();
        $Tutors =  Tuter::all();
        if(!$Tutors->isEmpty()){
            $datajson = json_encode($Tutors, JSON_UNESCAPED_UNICODE);
            $thedata["data"] = $datajson;
        }else{
            $thedata["situation"] = "False";
            $thedata["message"] = "معلمی برای نمایش در بانک نیست";
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
            $objectOfRequesr =  $newreq;
            $tutor = new Tuter;
            $tutor->user_id          = $objectOfRequesr->user_id;
            $tutor->user_profile_id  = $objectOfRequesr->user_profile_id;
            $tutor->preRegColleag_id = $objectOfRequesr->preRegColleag_id;
            if($tutor->save()){
                $thedata["situation"] = "OK";
                $thedata["message"] = $thedata["message"]."با موفقیت در بانک ذخیره شد";
                $thedata["data"] = $tutor;
                return json_encode($thedata, JSON_UNESCAPED_UNICODE);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tuter $tuter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tuter $tuter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tuter $tuter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tuter $tuter)
    {
        //
    }
}
