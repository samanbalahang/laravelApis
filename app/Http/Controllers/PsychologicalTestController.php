<?php

namespace App\Http\Controllers;

use App\Models\PsychologicalTest;
use Illuminate\Http\Request;
use App\Http\Controllers\ExtrasController;

class PsychologicalTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $theTest = new PsychologicalTest;
        $extrasController = new ExtrasController;
        $thedata = $extrasController->checkConnection(); 
        if($theTest::exists()){
            return json_encode($theTest, JSON_UNESCAPED_UNICODE);
        }else{
            $thedata["situation"] = "False";
            $thedata["message"] = "گالری پیدا نشد";
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PsychologicalTest $psychologicalTest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PsychologicalTest $psychologicalTest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PsychologicalTest $psychologicalTest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PsychologicalTest $psychologicalTest)
    {
        //
    }
}
