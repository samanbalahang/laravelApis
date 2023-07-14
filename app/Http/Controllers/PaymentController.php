<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Http\Controllers\ExtrasController;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $extrras = new ExtrasController;
        return($extrras->StartZibal());
    }

    public function result(Request $request){
        $extrras = new ExtrasController;
        $trackId = $request->trackId;
        $message = $request->message;
        $result = $request->result;
        $export = ["dargah"=>$extrras->ZibalPaymentStart($trackId)];
        if($result == 100){
            return json_encode($export, JSON_UNESCAPED_UNICODE);
        }else{
        return $extrras->ZibalResult($result);
        }
    }

    public function getPaymentResult(Request $request){
        $extrras = new ExtrasController;
        echo "success". $request->success;
        echo "<hr>";
        echo "trackId:". $request->trackId;
        echo "<hr>";
        echo "orderId:". $request->orderId; 
        echo "<hr>";
        echo "status".$request->status; 
        echo "<hr>";
        echo $extrras->ZibalPaymentStatus($request->status);
    }


    public function verify(Request $request){
        $extrras = new ExtrasController;
        $merchant = env("merchant", "zibal");
        $dargahMessage = $extrras->Zibalverify("zibal",$request->trackId);
        // $dargahMessage = $extrras->Zibalverify($merchant,$request->trackId);
        $dargahMessage->desc = $extrras->ZibalResult($dargahMessage->result);
        return json_encode($dargahMessage, JSON_UNESCAPED_UNICODE);
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
    
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
