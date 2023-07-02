<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Classes;
use App\Models\UserProfile;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Controllers\ExtrasController;
class CartController extends Controller
{
    // آیا بانک وصل است
    public function isDbconected(){
        $extrasController = new ExtrasController;
        if($extrasController ->isDatabaseConected() == "OK" ){
            return "OK";
        }else{
            return json_encode($extrasController -> checkConnection(), JSON_UNESCAPED_UNICODE);
        }
    }

    // جیسون اتصال به بانک
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
            $post = Cart::all();
            if(!$post->isEmpty()){
                $thedata["situation"] = true;
                $thedata["message"] = "در بانک یافت شد";
                $datajson = json_encode($post, JSON_UNESCAPED_UNICODE);
                $thedata["data"] = $datajson;
            }else{
                $thedata["situation"] = False;
                $thedata["message"] = "در بانک یافت نشد";
            }
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);

        }
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
            $classId = $request->class_id;
            $isindataBase = Cart::where("class_id", $classId)->where("user_id",$request->user_id)->first();
            if(!isset($isindataBase)){
                $newPost = new Cart;
                $newPost->user_id     =  $newreq->user_id;
                $newPost->user_profile_id  = $newreq->user_profile_id;
                $newPost->class_id  =  $newreq->class_id;
                if($newPost->save()){
                    $thedata["situation"] = true;
                    $thedata["message"] = $thedata["message"]."با موفقیت در بانک ذخیره شد";
                    $thedata["data"] = $newPost;
                    return json_encode($thedata, JSON_UNESCAPED_UNICODE);
                }else{
                    $thedata["situation"] = false;
                    $thedata["message"] = $thedata["message"]."در بانک ذخیره نشد";
                    return json_encode($thedata, JSON_UNESCAPED_UNICODE);
                }
            }else{
                $thedata["situation"] = false;
                $thedata["message"] ="قبلا در بانک ثبت شده است.";
                return json_encode($thedata, JSON_UNESCAPED_UNICODE);
           }
          }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        $extrasController = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection();
        $thecard = Cart::find($cart->id);
        if(isset($cart)){
            $thedata["data"] = $thecard;
            $theclass = Classes::find($thecard->class_id);
            if(isset($theclass)){
                $thedata["situation"] = true;
                $thedata["message"] = "در بانک یافت شد";
                $thedata["data"]["classes"] = $theclass;
            }
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }else{
            $thedata["situation"] = false;
            $thedata["message"] = "دربانک یافت نشد";
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        $extrasController = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection();

        $blog = Cart::find($cart->id);
        if($blog->delete()){
            $thedata["situation"] = true;
            $thedata["message"] = "با موفقیت حذف شد";
            $thedata["data"] = $cart;
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }

    }


    public function cartUser(Cart $cart,$id){
        $extrasController = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection();
        $cards = Cart::where("user_id",$id)->get();
        $allcards = [];
        if(!$cards->isEmpty()){
            foreach ($cards  as  $card ) {
                $card["classes"] = [];
                $theClass = Classes::find($card->class_id);
                if(isset($theClass)){

                    $card["classes"] = $theClass->toArray();;
                }
                array_push($allcards,$card);
            }
            $thedata["situation"] = true;
            $thedata["message"] = "در بانک یافت شد";
            $thedata["data"] =$allcards;
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }else{
            $thedata["situation"] = false;
            $thedata["message"] = "دربانک یافت نشد";
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }
    }
}
