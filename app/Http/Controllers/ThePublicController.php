<?php

namespace App\Http\Controllers;

use App\Models\ThePublic;
use App\Models\Post;
use App\Models\Collection;



use Illuminate\Http\Request;


class ThePublicController extends Controller
{

    
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
        $theCollection   = new Collection;
        $thePublics      = new ThePublic;
        $thedata = $this->getconnection();
        if($theCollection::exists() &&  $thePublics::exists()){
            $allColletion = $theCollection::all();
            $allpublics   = $thePublics::all();
            $exports = ["allColletion"=>$allColletion, "home-screen"=>$allpublics];
            $datajson = json_encode($exports, JSON_UNESCAPED_UNICODE);
            $thedata["data"] = $datajson;
            $thedata["situation"] = true;
            $thedata["message"] = "اطلاعات صفحه اصلی";
        }else{
            $thedata["situation"] = false;
            $thedata["message"] = "جداول شما خالی است";
        }
        return json_encode($thedata, JSON_UNESCAPED_UNICODE);
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
        $welcome_character = "";
        if(isset($request->welcome_character)){
            $welcome_character = $request->welcome_character;
        }
        $advertisement_banner = "";
        if(isset($request->advertisement_banner)){
            $advertisement_banner = $request->advertisement_banner;
        }
        $gallery_pic = "";
        if(isset($request->gallery_pic)){
            $gallery_pic = $request->gallery_pic;
        }
        $gallery_character = "";
        if(isset($request->gallery_character)){
            $gallery_character = $request->gallery_character;
        }
        $advertisement_left_banner = "";
        if(isset($request->advertisement_left_banner)){
            $advertisement_left_banner = $request->advertisement_left_banner;
        }
        $advertisement_right_banner = "";
        if(isset($request->advertisement_right_banner)){
            $advertisement_right_banner = $request->advertisement_right_banner;
        }
        $employment_character = "";
        if(isset($request->employment_character)){
            $employment_character = $request->employment_character;
        }

        // var_dump("welcome_charactername".$welcome_character);
        // var_dump("advertisement_banner".$advertisement_banner);
        // var_dump("gallery_pic".$gallery_pic);
        // var_dump("gallery_character".$gallery_character);
        // var_dump("advertisement_left_banner".$advertisement_left_banner);
        // var_dump("advertisement_right_banner".$advertisement_right_banner);
        // var_dump("employment_character".$employment_character);

        $photoAddress = "../uploads/post/";


        $welcome_charactername    =  $extrasController->imagefilename();
        $thefile      =  $photoAddress.$welcome_charactername.".png";
        $theTextFile  =  $photoAddress."a".".txt"; 
        if($welcome_character != ""){    
            // return $photoAddress;
           
            $fullAddress           = $photoAddress.$welcome_charactername.".png";
            if($myfile = fopen($thefile, "w")){
                $thedata["message"] = "عکس ذخیره شد";
            }
            // $myfile = fopen($fullAddress , "w") or die("Unable to open file!");
            fclose($myfile);
            $welcome_character= $extrasController->base64_to_jpeg($welcome_character,$photoAddress,$welcome_charactername);
        }

        $advertisement_bannername    =  $extrasController->imagefilename();
        $thefile      =  $photoAddress.$advertisement_bannername.".png";
        if($advertisement_banner != ""){    
            // return $photoAddress;
           
            $fullAddress           = $photoAddress.$advertisement_bannername.".png";
            if($myfile = fopen($thefile, "w")){
                $thedata["message"] = "عکس ذخیره شد";
            }
            // $myfile = fopen($fullAddress , "w") or die("Unable to open file!");
            fclose($myfile);
            $advertisement_banner= $extrasController->base64_to_jpeg($advertisement_banner,$photoAddress,$advertisement_bannername);
        }

        $gallery_picname    =  $extrasController->imagefilename();
        $thefile            =  $photoAddress.$gallery_picname.".png";
        if($gallery_pic != ""){    
            // return $photoAddress;
           
            $fullAddress           = $photoAddress.$gallery_picname.".png";
            if($myfile = fopen($thefile, "w")){
                $thedata["message"] = "عکس ذخیره شد";
            }
            // $myfile = fopen($fullAddress , "w") or die("Unable to open file!");
            fclose($myfile);
            $gallery_pic= $extrasController->base64_to_jpeg($gallery_pic,$photoAddress,$gallery_picname);
        }


        $gallery_charactername    =  $extrasController->imagefilename();
        $thefile            =  $photoAddress.$gallery_charactername.".png";
        if($gallery_character != ""){    
            // return $photoAddress;
           
            $fullAddress           = $photoAddress.$gallery_charactername.".png";
            if($myfile = fopen($thefile, "w")){
                $thedata["message"] = "عکس ذخیره شد";
            }
            // $myfile = fopen($fullAddress , "w") or die("Unable to open file!");
            fclose($myfile);
            $gallery_character= $extrasController->base64_to_jpeg($gallery_character,$photoAddress,$gallery_charactername);
        }


        $advertisement_left_bannername    =  $extrasController->imagefilename();
        $thefile                          =  $photoAddress.$advertisement_left_bannername.".png";
        if($advertisement_left_banner != ""){    
            // return $photoAddress;
           
            $fullAddress           = $photoAddress.$advertisement_left_bannername.".png";
            if($myfile = fopen($thefile, "w")){
                $thedata["message"] = "عکس ذخیره شد";
            }
            // $myfile = fopen($fullAddress , "w") or die("Unable to open file!");
            fclose($myfile);
            $advertisement_left_banner= $extrasController->base64_to_jpeg($advertisement_left_banner,$photoAddress,$advertisement_left_bannername);
        }



        $advertisement_right_bannername    =  $extrasController->imagefilename();
        $thefile                          =  $photoAddress.$advertisement_right_bannername.".png";
        if($advertisement_right_banner != ""){    
            // return $photoAddress;
           
            $fullAddress           = $photoAddress.$advertisement_right_bannername.".png";
            if($myfile = fopen($thefile, "w")){
                $thedata["message"] = "عکس ذخیره شد";
            }
            // $myfile = fopen($fullAddress , "w") or die("Unable to open file!");
            fclose($myfile);
            $advertisement_right_banner= $extrasController->base64_to_jpeg($advertisement_right_banner,$photoAddress,$advertisement_right_bannername);
        }


        $employment_charactername    =  $extrasController->imagefilename();
        $thefile                          =  $photoAddress.$employment_charactername.".png";
        if($employment_character != ""){    
            // return $photoAddress;
           
            $fullAddress           = $photoAddress.$employment_charactername.".png";
            if($myfile = fopen($thefile, "w")){
                $thedata["message"] = "عکس ذخیره شد";
            }
            // $myfile = fopen($fullAddress , "w") or die("Unable to open file!");
            fclose($myfile);
            $employment_character= $extrasController->base64_to_jpeg($employment_character,$photoAddress,$employment_charactername);
        }

        $unsetMediaReq = $request;
        unset($unsetMediaReq["welcome_character"]);
        unset($unsetMediaReq["advertisement_banner"]);
        unset($unsetMediaReq["gallery_pic"]);
        unset($unsetMediaReq["advertisement_left_banner"]);
        unset($unsetMediaReq["gallery_character"]);
        unset($unsetMediaReq["advertisement_right_banner"]);
        unset($unsetMediaReq["employment_character"]);

        $newreq = $extrasController->jsonRequestToObj($unsetMediaReq);
        if($newreq){              
            $savedAddress = "/uploads/post/";
            $newThePublic = new ThePublic;
            $newThePublic->welcome_text                =  $newreq->welcome_text;
            $newThePublic->employment_text             =  $newreq->employment_text;
            $newThePublic->employment_character        =  $savedAddress.$employment_charactername.'.jpg';
            $newThePublic->advertisement_right_banner  =  $savedAddress.$advertisement_right_bannername.'.jpg';
            $newThePublic->gallery_character           =  $savedAddress.$gallery_charactername.'.jpg';
            $newThePublic->advertisement_left_banner   =  $savedAddress.$advertisement_left_bannername.'.jpg';
            $newThePublic->gallery_pic                 =  $savedAddress.$gallery_picname.'.jpg';
            $newThePublic->advertisement_banner        =  $savedAddress.$advertisement_bannername.'.jpg';
            $newThePublic->welcome_character           =  $savedAddress.$welcome_charactername.'.jpg';
            if($newThePublic->save()){
                $thedata["situation"] = true;
                $thedata["message"] = $thedata["message"]."با موفقیت در بانک ذخیره شد";
                $thedata["data"] =  $newThePublic;
                return json_encode($thedata, JSON_UNESCAPED_UNICODE);
            };
        }

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
