<?php

namespace App\Http\Controllers;

use App\Models\Tuition;
use Illuminate\Http\Request;
use App\Http\Controllers\ExtrasController;

// شهریه
class TuitionController extends Controller
{
    // شهریه
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
    /**
     * Display a listing of the resource.
     */
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
        $tuition            = new Tuition;
        $tuition->class_id  = $request->class_id;
        $tuition->tuition   = $request->tuition;
        $tuition->theDate	= $request->theDate;
        $tuition->year      = $request->year;
        $tuition->month     = $request->month;
        $tuition->save();
        $thedata = $this->getconnection();
        return json_encode($thedata, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tuition $tuition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tuition $tuition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tuition $tuition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tuition $tuition)
    {
        //
    }
}
