<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\ExtrasController;
use App\Models\User;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // آیا بانک وصل است
    public function isDbconected(Request $request, $uri = "")
    {
        $extrasController = new ExtrasController;
        if ($extrasController->isDatabaseConected() == "OK") {
            return "OK";
        } else {
            return json_encode($extrasController->checkConnection(), JSON_UNESCAPED_UNICODE);
        }
    }


    // جزئیات اتصال به بانک به جیسون
    public function getconnection(Request $request, $uri = "")
    {
        $extrasController = new ExtrasController;
        $thedata = $extrasController->checkConnection();
        return $thedata;
    }

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
        $extrasController = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection($request);
        // اگر درخواست از اپ بصورت جیسون بود

        // دریافت شماره تلفن از کاربر
        $photo = "";
        if (isset($request->profilePhoto)) {
            $photo = $request->profilePhoto;
        }
        $photoAddress = "../uploads/usersProfile/";
        $photoname    =  $extrasController->imagefilename();
        $thefile      =  $photoAddress . $photoname . ".png";
        $theTextFile  =  $photoAddress . $photoname . ".txt";
        if ($photo != "") {
            // return $photoAddress;

            $fullAddress           = $photoAddress . $photoname . ".png";
            if ($myfile = fopen($thefile, "w")) {
                $thedata["message"] = "عکس در آدرس" . $fullAddress . "ذخیره شد" . "-";
            }
            // $myfile = fopen($fullAddress , "w") or die("Unable to open file!");
            fclose($myfile);
            $photo = $extrasController->base64_to_jpeg($photo, $photoAddress, $photoname);
        }
        if ($this->isDbconected($request) == "OK") {
            $unsetMediaReq = $request;
            unset($unsetMediaReq["profilePhoto"]);

            $newreq = $extrasController->jsonRequestToObj($unsetMediaReq);

            $passCode = $extrasController->createPasshash($newreq->passCode);
            if ($newreq) {
                $savedAddress = "/uploads/usersProfile/";
                $newUser = new UserProfile;
                $setonuser = new User;
                $setonuser->name = $newUser->fullName;
                $noemal = "nomail" . strval($extrasController->createfourRandumDigit()) . "@" . strval($extrasController->createfourRandumDigit()) . ".com";
                $setonuser->email = $noemal;
                $setonuser->checkhash = $extrasController->createhash($newreq->nationalCode);
                $setonuser->save();
                $newUser->user_id                    =  $setonuser->id;
                $newUser->roleID                     =  $newreq->roleID;
                $newUser->fullName                   =  $newreq->fullName;
                $newUser->fName                      =  $newreq->fName;
                $newUser->lName                      =  $newreq->lName;
                $newUser->nationalCode               =  $newreq->nationalCode;
                $newUser->phoneNumber                =  $newreq->phoneNumber;
                $newUser->gender                     =  $newreq->gender;
                $newUser->passCode                   =  $passCode;
                $newUser->fatherHusbandName          =  $newreq->fatherHusbandName;
                $newUser->profilePhoto               =  $savedAddress . $photoname . '.jpg';
                $newUser->fatherHusbndphoneNumber    =  $newreq->fatherHusbndphoneNumber;
                $newUser->gayemjob                   =  $newreq->gayemjob;
                $newUser->postalCode                 =  $newreq->postalCode;
                $newUser->insuranceSitu              =  $newreq->insuranceSitu;
                $newUser->mainPhoneNumber            =  $newreq->mainPhoneNumber;
                $newUser->askForSallery              =  $newreq->askForSallery;
                if ($newUser->save()) {
                    $thedata["situation"] = true;
                    $thedata["message"] = $thedata["message"] . "با موفقیت در بانک ذخیره شد";
                    $thedata["data"] = $newUser;
                    $thedata["data"]["passcode"] = $newreq->passCode;
                    $thedata["data"]["hash"] = $passCode;


                    return json_encode($thedata, JSON_UNESCAPED_UNICODE);
                };
            } else {
                $thedata["situation"] = false;
                $thedata["message"] = $thedata["message"] . "newreq ساخته نشد";
                // $thedata["data"] = $newUser;
                return json_encode($thedata, JSON_UNESCAPED_UNICODE);
            }
        } else {
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UserProfile $userProfile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserProfile $userProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserProfile $userProfile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserProfile $userProfile)
    {
        //
    }
    /**
     *  گرفتن شماره تلفن و کد های چهار رقمی
     */
    public function getPhone(Request $request)
    {
        $extrasController = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection($request);
        // اگر درخواست از اپ بصورت جیسون بود
        if ($extrasController->isJson($request)) {
            // دریافت شماره تلفن از کاربر
            $phoneNumber = $request["phoneNumber"];
            //  ایجاد کد چهار رقمی
            $fourDegits = $extrasController->createfourRandumDigit();
            // ایجاد هش
            $checkhash =  $extrasController->createhash($phoneNumber);
            // اگر کانکشن برقرار است
            if ($this->isDbconected($request) == "OK") {
                // آیا این شماره موبایل ثبت شده است
                $user = User::where("phoneNumber", $phoneNumber)->first();
                if (isset($user)) {
                    // هش و کد چهار رقمی را برگردان
                    $thedata["data"] =  $fourDegits;
                    $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
                    return $myJSON;
                } else {
                    /* شماره موبایل ثبت نیست و کاربر را ساخته و 
                                کد چهار رقمی و هش را برای کاربر ارسال میکنیم
                                */
                    $user = new User;
                    $user->phoneNumber = $phoneNumber;
                    $user->fourDegits  = $fourDegits;
                    $user->checkhash   =  $checkhash;
                    if ($user->save()) {
                        $thedata["data"] = $user;
                        $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
                        return $myJSON;
                    }
                }
            } else {
                // اگر کانکشن برقرار نیست خطا با جیسون برمیگردد.
                $myObj = ["phoneNumber" => $phoneNumber];
                $myObj = (object) $myObj;
                $thedata["data"] = $myObj;
                $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
                return $myJSON;
            }
            $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
            return $myJSON;
            return response()->json($request->all());
        }
        //  درخواست سمت وب برای تست
        else {
            if (isset($request["fromApp"])) {
                // درخواست غیر جیسونی از اپ
                $fromApp = $request["fromApp"];
                if ($fromApp == "ok") {
                    // دریافت شماره تلفن از کاربر
                    $phoneNumber = $request["phoneNumber"];
                    //  ایجاد کد چهار رقمی
                    $fourDegits = $extrasController->createfourRandumDigit();
                    // ایجاد هش
                    $checkhash =  $extrasController->createhash($phoneNumber);
                    // اگر کانکشن برقرار است
                    if ($this->isDbconected($request) == "OK") {
                        // آیا این شماره موبایل ثبت شده است
                        $user = User::where("phoneNumber", $phoneNumber)->first();
                        if (isset($user)) {
                            // هش و کد چهار رقمی را برگردان
                            // $myObj = ["phoneNumber" => $phoneNumber, "checkhash" => $checkhash, "fourDegits" => $fourDegits];
                            // $myObj = (object) $myObj;
                            // $thedata["data"] =  $myObj;
                            $thedata["data"] =  $user;

                            $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
                            return $myJSON;
                        } else {
                            /* شماره موبایل ثبت نیست و کاربر را ساخته و 
                                کد چهار رقمی و هش را برای کاربر ارسال میکنیم
                                */
                            $user = new User;
                            $user->phoneNumber = $phoneNumber;
                            $user->fourDegits  = $fourDegits;
                            $user->checkhash   =  $checkhash;
                            if ($user->save()) {
                                $thedata["data"] = $user;
                                $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
                                return $myJSON;
                            }
                        }
                    } else {
                        // اگر کانکشن برقرار نیست خطا با جیسون برمیگردد.
                        $myObj = ["phoneNumber" => $phoneNumber, "checkhash" => $checkhash, "fourDegits" => $fourDegits];
                        $myObj = (object) $myObj;
                        $thedata["data"] =  $myObj;
                        $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
                        return $myJSON;
                    }
                    $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
                    return $myJSON;
                    return response()->json($request->all());
                }
            } else {
                // درخواست از سمت وب است
                // دریافت شماره تلفن از کاربر
                $phoneNumber = $request["phoneNumber"];
                //  ایجاد کد چهار رقمی
                $fourDegits = $extrasController->createfourRandumDigit();
                // ایجاد هش
                $checkhash =  $extrasController->createhash($phoneNumber);
                // اگر کانکشن برقرار است
                if ($this->isDbconected($request) == "OK") {
                    // آیا این شماره موبایل ثبت شده است
                    $user = User::where("phoneNumber", $phoneNumber)->first();
                    if (isset($user)) {
                        // هش و کد چهار رقمی را برگردان
                        $thedata["data"] =  $fourDegits;
                        $data = $thedata["data"];
                        return redirect()->route('test.create')->with(['data' => $fourDegits, "checkhash" => $checkhash, "phoneNumber" => $phoneNumber]);
                    } else {
                        /* شماره موبایل ثبت نیست و کاربر را ساخته و 
                    کد چهار رقمی و هش را برای کاربر ارسال میکنیم
                    */
                        $user = new User;
                        $user->phoneNumber = $phoneNumber;
                        $user->fourDegits  = $fourDegits;
                        $user->checkhash   =  $checkhash;
                        if ($user->save()) {
                            $thedata = $request->all();
                            $data = $thedata["data"];
                            return redirect()->route('test.create')->with(['data' => $thedata, "checkhash" => $checkhash, "phoneNumber" => $phoneNumber]);
                        }
                    }
                } else {
                    // اگر کانکشن برقرار نیست خطا با جیسون برمیگردد.
                    $myObj = ["phoneNumber" => $phoneNumber];
                    $myObj = (object) $myObj;
                    // $thedata["data"] = $myObj;
                    // $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
                    // return $myJSON;
                    return $myObj;
                }
            }
        }
    }

    public function forgotPassword(Request $request)
    {
        $extrasController = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection($request);
        // اگر درخواست از اپ بصورت جیسون بود
        $userId = $request->user_id;
        $pass =  $request->pass;
        $newpass = $extrasController->createPasshash($pass);
        $user = new User;
        $theuser = $user::find($userId);
        if (isset($theuser->id)) {
            $theuser->password = $newpass;
            $theuser->save();
        }
        $userProfile = new UserProfile;
        $theprofile  = $userProfile::where("user_id", $userId)->first();
        if (isset($theprofile->id)) {
            $theprofile->passCode = $newpass;
            if ($theprofile->save()) {
                $thedata["situation"] = true;
                $thedata["message"] = "رمز تغییر کرد";
                $thedata["data"] = $theuser;
                $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
                return $myJSON;
            } else {
                $thedata["situation"] = false;
                $thedata["message"] = "ذخیره نشد";
                $thedata["data"] = $user;
                $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
                return $myJSON;
            }
        } else {
            $thedata["situation"] = false;
            $thedata["message"] = "کاربر یافت نشد";
            $thedata["data"] = $user;
            $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
            return $myJSON;
        }
    }

    public function getDigit(Request $request)
    {
        $extrasController = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection($request);
        // اگر درخواست از اپ بصورت جیسون بود
        if ($extrasController->isJson($request)) {
            // دریافت شماره تلفن از کاربر
            $phoneNumber = $request["phoneNumber"];
            $fourDegits  = $request["fourDegits"];
            $checkhash  = $request["checkhash"];
            // اگر کانکشن برقرار است
            if ($this->isDbconected($request) == "OK") {
                // آیا این شماره موبایل ثبت شده است
                $user = User::where("phoneNumber", $phoneNumber)->where("fourDegits", $fourDegits)->first();
                if (isset($user->id)) {
                    if ($user->checkhash == $checkhash) {
                        $thedata["situation"] = true;
                        // هش و کد چهار رقمی را برگردان
                        $thedata["data"] =  $user;
                        $thedata["message"] = "تست تایید" . "ورود کاربر تایید شد";
                        $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
                        return $myJSON;
                    }
                } else {
                    // کاربر در بانک نیست
                    $thedata["data"] =  "";
                    $thedata["message"] =  "ورود کاربر تایید نشد";
                    $thedata["situation"] = false;
                    $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
                    return $myJSON;
                }
            } else {
                // اگر کانکشن برقرار نیست خطا با جیسون برمیگردد.
                $myObj = ["phoneNumber" => $phoneNumber];
                $myObj = (object) $myObj;
                $thedata["data"] = $myObj;
                $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
                return $myJSON;
            }
            $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
            return $myJSON;
            return response()->json($request->all());
        }
        //  درخواست سمت وب برای تست
        else {
            // درخواست غیر جیسونی 
            // دریافت شماره تلفن از کاربر
            $phoneNumber = $request["phoneNumber"];
            $fourDegits  = $request["fourDegits"];
            $checkhash  = $request["checkhash"];
            // اگر کانکشن برقرار است
            if ($this->isDbconected($request) == "OK") {
                // آیا این شماره موبایل ثبت شده است
                $user = User::where("phoneNumber", $phoneNumber)->where("fourDegits", $fourDegits)->first();
                if (isset($user)) {
                    if ($user->checkhash == $checkhash) {
                        // هش و کد چهار رقمی را برگردان
                        $thedata["situation"] = true;
                        $thedata["message"] =  "ورود کاربر تایید شد";
                        $thedata["data"] =  $user;
                        $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
                        return $myJSON;
                    } else {
                        $thedata["situation"] = false;
                        $thedata["message"] = "هش برابر نیست";
                    }
                } else {
                    // کاربر در بانک نیست
                    $thedata["message"] =  "ورود کاربر تایید نشد";
                    $thedata["situation"] = false;
                    $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
                    return $myJSON;
                }
            } else {
                // اگر کانکشن برقرار نیست خطا با جیسون برمیگردد.
                $thedata["message"] =  "isDbconected";
                $thedata["situation"] = false;
                $myObj = ["phoneNumber" => $phoneNumber];
                $myObj = (object) $myObj;
                $thedata["data"] = $myObj;
                $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
                return $myJSON;
            }
            $thedata["message"] =  "nothimg returns";
            $thedata["situation"] = false;
            $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
            return $myJSON;
            return response()->json($request->all());
        }
    }

    public function login(Request $request)
    {
        $extrasController = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection($request);
        if ($this->isDbconected($request) == "OK") {
            $newreq = $extrasController->jsonRequestToObj($request);
            $nationlcode =  $request->nationlcode;
            $passCode    =  $request->passCode;
            $user = UserProfile::where("nationalCode", $nationlcode)->first();
            $user_id = $user->user_id;
            $theuser = User::find($user_id);
            $user["token"] =  $theuser->checkhash;
            $extrasController->createhash($passCode);
            if (isset($user)) {
                if ($extrasController->checkHash($passCode, $user->passCode)) {
                    $thedata["situation"] = true;
                    $thedata["message"] = "کاربر در بانک وجود دارد";
                    $thedata["data"] = $user;
                    return  json_encode($thedata, JSON_UNESCAPED_UNICODE);
                } else {
                    if ($extrasController->createhash($passCode) != $user->passCode) {
                        $thedata["situation"] = false;
                        $thedata["message"] = $extrasController->createhash($passCode) . "__" . $user->passCode;
                        // $thedata["message"] = $passCode."__".$user->passCode;
                        $thedata["message"] = "رمز عبور کاربر همخوانی ندارد!";
                        $thedata["data"] = $user;
                    } else {
                        $thedata["data"] = $user;
                    }
                    return  json_encode($thedata, JSON_UNESCAPED_UNICODE);
                }
            } else {
                // کاربر در بانک نیست
                $thedata["message"] =  "شماره ملی کاربر یافت نشد";
                $thedata["situation"] = false;
                $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
                return $myJSON;
            }
            $thedata["message"] =  "nothimg returns";
            $thedata["situation"] = false;
            $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
            return $myJSON;
        } else {
            $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
            return $myJSON;
        }
    }





    // USERPANEl
    public function info(Request $request)
    {
        $headers = apache_request_headers();
        $token = $headers['Authorization'];
        $user_id = $headers['user_id'];
        // dd($headers['Authorization']);
        $extrasController = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection($request);
        if ($this->isDbconected($request) == "OK") {
            $newreq = $extrasController->jsonRequestToObj($request);
            // $nationlcode =  $request->nationlcode;
            // $passCode    =  $request->passCode;
            $user = UserProfile::where("user_id", $user_id)->first();
            $user["tuition_paid"] = 0;
            $user["tuition_remind"] = 0;
            $user["wallet_balance"] = 0;
            // $user_id = $user->user_id;
            $theuser = User::find($user_id);
            $user["token"] =  $theuser->checkhash;
            // $extrasController->createhash($passCode);
            if (isset($user)) {
                // var_dump($token);
                // var_dump($theuser->checkhash);
                // dd(($token == $theuser->checkhash));
                if ($token == $theuser->checkhash) {
                    $thedata["situation"] = true;
                    $thedata["message"] = "کاربر در بانک وجود دارد";
                    $thedata["data"] = $user;
                    return  json_encode($thedata, JSON_UNESCAPED_UNICODE);
                } else {
                    if ($extrasController->createhash($token) != $user->checkhash) {
                        $thedata["situation"] = false;
                        $thedata["message"] = "";
                        // $thedata["message"] = $passCode."__".$user->passCode;
                        $thedata["message"] = "رمز عبور کاربر همخوانی ندارد!";
                        $thedata["data"] = "{}";
                    } else {
                        $thedata["data"] = "{}";
                    }
                    return  json_encode($thedata, JSON_UNESCAPED_UNICODE);
                }
            } else {
                // کاربر در بانک نیست
                $thedata["message"] =  "شماره ملی کاربر یافت نشد";
                $thedata["situation"] = false;
                $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
                return $myJSON;
            }
            $thedata["message"] =  "nothimg returns";
            $thedata["situation"] = false;
            $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
            return $myJSON;
        } else {
            $myJSON = json_encode($thedata, JSON_UNESCAPED_UNICODE);
            return $myJSON;
        }
    }

    // ویرایش کاربر
    public function editinfo(Request $request)
    {
        $headers = apache_request_headers();
        $extrasController = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection($request);
        // اگر درخواست از اپ بصورت جیسون بود

        // دریافت شماره تلفن از کاربر
        $photo = "";
        if (isset($request->profilePic)) {
            $photo = $request->profilePic;
        }
        $photoAddress = "../uploads/usersProfile/";
        $photoname    =  $extrasController->imagefilename();
        $thefile      =  $photoAddress . $photoname . ".png";
        $theTextFile  =  $photoAddress . $photoname . ".txt";
        // dd($photo);
        if ($photo != "") {
            // return $photoAddress;
            $fullAddress           = $photoAddress . $photoname . ".png";
            if ($myfile = fopen($thefile, "w")) {
                $thedata["message"] = "عکس در آدرس" . $fullAddress . "ذخیره شد" . "-";
            }
            // $myfile = fopen($fullAddress , "w") or die("Unable to open file!");
            fclose($myfile);
            $photo = $extrasController->base64_to_jpeg($photo, $photoAddress, $photoname);
        }
        if ($this->isDbconected($request) == "OK") {
            $unsetMediaReq = $request;
            unset($unsetMediaReq["profilePic"]);
            $newreq = $extrasController->jsonRequestToObj($unsetMediaReq);
            // $passCode = $extrasController->createPasshash($newreq->passCode);
            if ($newreq) {
                $savedAddress = "/uploads/usersProfile/";
                $thisUser = new UserProfile;
                $token = $headers['Authorization'];
                $user_id = $headers['user_id'];
                $newUser = $thisUser::where("user_id", $user_id)->first();
                $newUser->nationalCode               =  $newreq->nationalCode;
                $newUser->fatherHusbandName          =  $newreq->fatherName;
                $newUser->motherName                 =  $newreq->motherName;
                $newUser->born                       =  $newreq->born;
                $newUser->grade                      =  $newreq->grade;
                $newUser->phoneNumber                =  $newreq->phoneNumber;
                $newUser->profilePhoto               =  $savedAddress . $photoname . '.jpg';
                if ($newUser->save()) {
                    $thedata["situation"] = true;
                    $thedata["message"] = $thedata["message"] . "با موفقیت در بانک ذخیره شد";
                    $thedata["data"] = $newUser;
                    // $thedata["data"]["passcode"] =$newreq->passCode;
                    // $thedata["data"]["hash"] = $passCode;
                    return json_encode($thedata, JSON_UNESCAPED_UNICODE);
                } else {
                    $thedata["situation"] = false;
                    $thedata["message"] = $thedata["message"] . "خطای ذخیره شدن";
                }
            } else {
                $thedata["situation"] = false;
                $thedata["message"] = $thedata["message"] . "newreq ساخته نشد";
                // $thedata["data"] = $newUser;
                return json_encode($thedata, JSON_UNESCAPED_UNICODE);
            }
        } else {
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }
    }

    // ارسال فایل
    public function personalFile(Request $request)
    {
        $headers = apache_request_headers();
        $extrasController = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection($request);
        $savedAddress = "/uploads/usersProfile/";
        $photo = "";
        if (isset($request->file)) {
            $photo = $request->file;
        }
        if ($photo != "") {
            $savefile = $extrasController->simpleFileUpload($request, "file", $savedAddress);
        }
        if ($this->isDbconected($request) == "OK") {
            $unsetMediaReq = $request;
            unset($unsetMediaReq["file"]);
            $newreq = $extrasController->jsonRequestToObj($unsetMediaReq);
            if ($newreq) {
            }
        }
    }
}
