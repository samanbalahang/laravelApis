<?php

namespace App\Http\Controllers;

use App\Models\CollectionCat;
use Illuminate\Http\Request;

class CollectionCatController extends Controller
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

    public function index()
    {
        $extrasController = new ExtrasController;
        $thedata = $this->getconnection();
        if($this->isDbconected() == "OK"){
            $collectionCat = CollectionCat::all();
            $thedata["data"] = $collectionCat ;
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
        if($this->isDbconected() == "OK"){
            $newreq = $extrasController->jsonRequestToObj($request);
            if($newreq){
                $newCollectionCat = new  CollectionCat;
                $newCollectionCat->collection_id    =  $newreq->collection_id;
                $newCollectionCat->catName          =  $newreq->catName;
                $newCollectionCat->gallery_id      =  $newreq->gallery_id;
                if($newCollectionCat->save()){
                    $thedata["situation"] = "OK";
                    $thedata["message"] = "با موفقیت در بانک ذخیره شد";
                    $thedata["data"] = $newCollectionCat;
                    return json_encode($thedata, JSON_UNESCAPED_UNICODE);
                }else{
                    $thedata["situation"] = false;
                    $thedata["message"] = "خطا در ذخیره داده";
                    return json_encode($thedata, JSON_UNESCAPED_UNICODE);
                }
            }else{
                $thedata["situation"] = false;
                $thedata["message"] = "خطا در newreq";
                return json_encode($thedata, JSON_UNESCAPED_UNICODE);
            }

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CollectionCat $collectionCat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CollectionCat $collectionCat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CollectionCat $collectionCat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CollectionCat $collectionCat)
    {
        //
    }
}
