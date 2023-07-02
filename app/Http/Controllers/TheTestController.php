<?php

namespace App\Http\Controllers;

use App\Models\theTest;
use Illuminate\Http\Request;
use App\Http\Controllers\ExtrasController;

class TheTestController extends Controller
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
    public function index()
    {
        //

        $extrasController = new ExtrasController;
        // var_dump($extrasController -> pngTojpg());
        // var_dump($extrasController -> base64_to_jpeg("base64,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",'testimg'));
        $myObj["name"] = "John";
        $myObj["age"] = 30;
        $myObj["city"] = "New York";
        $myObj["token"] = csrf_token();
        $myObj = (object) $myObj;
        $thedata = $extrasController->checkConnection(); 
        $thedata["data"] =  $myObj;
        // $checkConnection = $extrasController->checkConnection();
        // $createFinalexport = $checkConnection; 
        
        // $newarray["data"] = $myObj;  
        // array_push($createFinalexport,$newarray);
        // dd($thedata);
        $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
        echo $myJSON;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if($data = $request->session()->get('data')){
            $data = $request->session()->get('data');
            $checkhash = $request->session()->get('checkhash');
            $phoneNumber = $request->session()->get('phoneNumber');
            return view('test', compact("data","checkhash","phoneNumber"));
        }else{
             return view('test');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(theTest $theTest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(theTest $theTest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, theTest $theTest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(theTest $theTest)
    {
        //
    }
}
