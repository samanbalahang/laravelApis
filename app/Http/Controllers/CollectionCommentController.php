<?php

namespace App\Http\Controllers;

use App\Models\CollectionComment;
use Illuminate\Http\Request;

class CollectionCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function isDbconected(Request $request,$uri = ""){
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
    public function index()
    {
        //
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
                $newCollectionComment = new  CollectionComment;
                $newCollectionComment->user_id                  =  $newreq->user_id;
                $newCollectionComment->user_profile_id          =  $newreq->user_profile_id;
                $newCollectionComment->pre_reg_id               =  $newreq->pre_reg_id;
                $newCollectionComment->pre_reg_collige_id       =  $newreq->pre_reg_collige_id;
                $newCollectionComment->collection_id            =  $newreq->collection_id;
                $newCollectionComment->comment                  =  $newreq->comment;
                $newCollectionComment->approved                  =  $newreq->approved;
                if($newCollectionComment->save()){
                    $thedata["situation"] = "OK";
                    $thedata["message"] = "با موفقیت در بانک ذخیره شد";
                    $thedata["data"] = $newCollectionComment;
                    return json_encode($thedata, JSON_UNESCAPED_UNICODE);
                }else{
                    $thedata["situation"] = "false";
                    $thedata["message"] = "خطا در ذخیره داده";
                    return json_encode($thedata, JSON_UNESCAPED_UNICODE);
                }
            }else{
                $thedata["situation"] = "false";
                $thedata["message"] = "داده دریافتی خطایی دارد!";
                return json_encode($thedata, JSON_UNESCAPED_UNICODE);
            }      
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CollectionComment $collectionComment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CollectionComment $collectionComment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CollectionComment $collectionComment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CollectionComment $collectionComment)
    {
        //
    }
}
