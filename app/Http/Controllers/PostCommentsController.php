<?php

namespace App\Http\Controllers;

use App\Models\postComments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ExtrasController;

class PostCommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // آیا بانک وصل است
    public function isDbconected(){
        $extrasController = new ExtrasController;
        if($extrasController ->isDatabaseConected() == "OK" ){
            return "OK";
        }else{
            return json_encode($extrasController -> checkConnection(), JSON_UNESCAPED_UNICODE);
        }
    }

    // یک متغیر پایه دیتا براساس اتصال پس میدهد جیسون خواهد شد.
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
            $postcomments = postComments::all();
            if(!$postcomments->isEmpty()){
                $datajson = json_encode($postcomments, JSON_UNESCAPED_UNICODE);
                $thedata["data"] = $datajson;
            }else{
                $thedata["situation"] = "False";
                $thedata["message"] = "پستی برای پیدا نشد";
            }
        }else{
            $thedata["situation"] = "False";
            $thedata["message"] = "جدول نوع پست خالی است posts_types";
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
                $PostComment = new postComments;
                $PostComment->post_id           =  $newreq->post_id;
                $PostComment->post_uri          =  $newreq->post_uri;
                $PostComment->user_id           =  $newreq->user_id;
                $PostComment->user_profile_id   =  $newreq->user_profile_id;
                $PostComment->user_preReg_id    =  $newreq->user_preReg_id;
                $PostComment->user_preRegCol_id =  $newreq->user_preRegCol_id;
                $PostComment->comment           =  $newreq->comment;
                $PostComment->approved          =  $newreq->approved;
                if($PostComment->save()){
                    $thedata["message"] = "با موفقیت در بانک ذخیره شد";
                    $thedata["data"] =  $newreq;
                    return json_encode($thedata, JSON_UNESCAPED_UNICODE);
                }
            }

        }
        $thedata["situation"] = False;
        $thedata["message"] = "خطایی رخ داده است";
        $thedata["data"] =  $newreq;
        return json_encode($thedata, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Display the specified resource.
     */
    public function show(postComments $postComments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(postComments $postComments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, postComments $postComments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(postComments $postComments)
    {
        //
    }
}
