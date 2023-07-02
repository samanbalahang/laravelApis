<?php

namespace App\Http\Controllers;

use App\Models\ThePublic;
use App\Models\Post;
use App\Models\Collection;



use Illuminate\Http\Request;


class ThePublicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $theCollection   = new Collection;
        $thePublics      = new ThePublic;
        if($theCollection::exists() &&  $thePublics::exists()){
            $allColletion = $theCollection::all();
            
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ThePublic $thePublic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ThePublic $thePublic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ThePublic $thePublic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ThePublic $thePublic)
    {
        //
    }
}
