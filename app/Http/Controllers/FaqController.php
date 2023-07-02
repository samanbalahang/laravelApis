<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use App\Http\Controllers\ExtrasController;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // آیا به بانک متصلیم
    public function isDbconected(){
        $extrasController = new ExtrasController;
        if($extrasController ->isDatabaseConected() == "OK" ){
            return "OK";
        }else{
            return json_encode($extrasController -> checkConnection(), JSON_UNESCAPED_UNICODE);
        }
    }

    //دریافت جیسون وضعیت بانک
    public function getconnection(){
        // یک متغیر پایه دیتا براساس اتصال پس میدهد جیسون خواهد شد.
        $extrasController = new ExtrasController;
        $thedata = $extrasController->checkConnection(); 
        return $thedata;
    }

    public function index()
    {
       
        $thedata = $this->getconnection();
        if($this->isDbconected() == "OK"){
            $Faqmodel =new Faq;
            $allFaq = $Faqmodel::all();

            if(!$allFaq ->isEmpty()){
                $datajson = json_encode($allFaq, JSON_UNESCAPED_UNICODE);
                $thedata["data"] = $datajson;
                return json_encode($thedata, JSON_UNESCAPED_UNICODE);
            }else{
                $thedata["situation"] = "False";
                $thedata["message"] = "نظری برای ارسال وجود ندارد.";
                return json_encode($thedata, JSON_UNESCAPED_UNICODE);   
            }      
        }
     
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
       $thedata = $this->getconnection();

       if($this->isDbconected($request) == "OK"){
            $newreq = $extrasController->jsonRequestToObj($request);

            $newFaq = new Faq;
            $newFaq->question  =  $newreq->question;
            $newFaq->answear         =  $newreq->answear;
            if($newFaq->save()){
                $thedata["situation"] = "OK";
                $thedata["message"] = $thedata["message"]."با موفقیت در بانک ذخیره شد";
                $thedata["data"] =  $newFaq;
                return json_encode($thedata, JSON_UNESCAPED_UNICODE);
            }else{
                $thedata["situation"] = False;
                $thedata["message"] = $thedata["message"]."خطایی رخداد";
                $thedata["data"] =  $newFaq;
                return json_encode($thedata, JSON_UNESCAPED_UNICODE);
            }
        }else{
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Faq $faq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faq $faq)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faq $faq)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq)
    {
        //
    }
}
