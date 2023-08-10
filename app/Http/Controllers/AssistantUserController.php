<?php

namespace App\Http\Controllers;

use App\Models\AssistantUser;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Role;
use App\Http\Controllers\ExtrasController;

class AssistantUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

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
        $thedata = $extrasController->checkConnection(); 
        $userModel = new User;
        $userProfModel = new UserProfile;
        $roleModel = new Role;
        $national_code = $request->national_code;
        $theuser = $userProfModel::where("nationalCode",$national_code)->first();
        if($theuser){
            $thedata["situation"] = true;
            $thedata["message"] = "";
            $thedata["data"] = $theuser;
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }else{
            $thedata["situation"] = False;
            $thedata["message"] = "کاربری یافت نشد";
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AssistantUser $assistantUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssistantUser $assistantUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AssistantUser $assistantUser)
    {
        
    }
    public function updateInfo(Request $request)
    {
        $extrasController = new ExtrasController;
        $thedata = $extrasController->checkConnection(); 
        $userModel = new User;
        $userProfModel = new UserProfile;
        $roleModel = new Role;
        $roleId = 0;
        $national_code = $request->national_code;
        $theuser = $userProfModel::where("nationalCode",$national_code)->first();
        if($roleModel::exists()){
            $therole = $roleModel::where("name","assistant")->first();
            if(!$therole){
                $roleModel->name = "assistant";
                $roleModel->save();

            }else{
                $roleId =  $roleModel->id;
            }
        }else{
            $roleModel->name = "assistant";
            $roleModel->save();
        }
        if($roleId != 0){
            $roleId =  $roleModel -> id;
        }
        if($theuser){
            $theuser->fatherHusbandName = $request->father_name;
            $theuser->born              = $request->born;
            $theuser->position          = $request->position;
            $theuser->phoneNumber      = $request->phone_number;
            $theuser->points            = $request->points;
            $theuser->save();
            $thedata["situation"] = true;
            $thedata["message"] = "";
            $thedata["data"] = $theuser;
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }else{
            $thedata["situation"] = False;
            $thedata["message"] = "کاربری یافت نشد";
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssistantUser $assistantUser)
    {
        //
    }
}
