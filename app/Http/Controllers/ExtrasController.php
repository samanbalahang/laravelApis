<?php

namespace App\Http\Controllers;

use App\Models\Extras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/*------------------------------------
|
|
|
|      کنترلر برای چک کانکشن است
|
|
|--------------------------------------*/

class ExtrasController extends Controller
{
    /**
     * چک کردن اتصال به بانک و بازگردانی آن بصورت یک آرایه
     *
     */
    public function checkConnection(){
        try {
            $dbconnect = DB::connection()->getPDO();
            $dbname = DB::connection()->getDatabaseName();
            $exportToApp =$this->makeExportJson();
            return $exportToApp;
         } catch(\PDOException $e) {
            $situation   = "False";
            $message     ="اتصال به بانک برقرار نیست";
            $data        = "";
            $exportToApp =$this->makeExportJson($situation,$message,$data);
            return $exportToApp;
         }
    }

        /*
        |   
        |       اگر اتصال برقرار باشد    
        |           OK
        |            در غیر این صورت 
        |       False
        |            بر میگرداند
        */
    public function isDatabaseConected(){

        return $this->checkConnection()["situation"];
    }

            /*
        | آیا ورودی جیسون است.
        */
    public function isJson($string) {
        json_decode($string);
        if(json_last_error() === JSON_ERROR_NONE){
            return true;
        }else{
            return false;
        }

    }

    // ایجاد یک عدد تصادفی چهار رقمی
    public function createfourRandumDigit() {
        $rand = mt_rand(1000,9999);
        return $rand;       
    }

    //  ایجاد یک هش
    public function createhash($baseString = "") {
        if($baseString != ""){
            return $this->clean(strval(Hash::make($baseString)));
        }else{
            return Hash::make();
        }
    }
  
    public function createPasshash($baseString = "") {
        return  Hash::make($baseString);
    }


    public function checkHash($baseValue = "",$databasehash = ""){
        return Hash::check($baseValue, $databasehash);
    }

    function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
     
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
     }

    // تبدیل عکس 64 بیت به عکس معمولی
    function base64_to_jpeg($base64_string, $output_file,$photoname="") {
        // تبدیل بیس 64 به عکس
        // open the output file for writing
        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode(',', $base64_string );
        $data = base64_decode($data[1]);
        $images = imageCreateFromString($data);
        if($images  !== false ){
            header( "Content-type: image/png" );
        
            // imagepng($images);
            // return "hi";
            if(strpos(strval(public_path()),"\\")>0){
                $seprator = "\\";
            }else{
                $seprator = "/";
            }
            $photoAddress= $output_file.$seprator.$photoname.'.png';
            imagepng($images,$photoAddress);
            imagedestroy($images);
            if($convert = $this->pngTojpg($photoAddress)){
                unlink($photoAddress);
            }
        }    

        return  $convert;

    }

    // ایجاد نام فایل
    public function imagefilename($length = 4){
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        $randomString = trim(strval(date("Y-m-d")))."-".trim($randomString);
        return trim($randomString);
    }

    // تبدیل جیسون به شیئ 
    public function jsonRequestToObj($request){
        $a = $request->all();  
        $data = response()->json($request->all(), 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
        // $data = response()->json($request->all());
        $length = (strpos($data,"}") - strripos($data,"{"))+1;
        $json= str_replace('\\', '',(strval(substr($data ,strripos($data,"{"),$length))));
        if($this->isJson($json)){
            // return $json;
            $theobj = json_decode($json);
            // return  $theobj->postTypeId;
            return $theobj;
        }else{    
            return ($this->isJson($json));    
        }
    }

    // تبدیل عکس به حجم کمتر با فرمت درست
    public function pngTojpg($filePath = "/testimg/test.png"){
        // $filePath = public_path() . $filePath;
        $filePath = $filePath;
        $image = imagecreatefrompng($filePath);
        $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagealphablending($bg, TRUE);
        imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
        imagedestroy($image);
        $quality = 100; // 0 = worst / smaller file, 100 = better / bigger file 
        $filename = strval(substr(strval($filePath),0,strpos($filePath,".png")));
        if(imagejpeg($bg,  $filename . ".jpg", $quality)){
            imagedestroy($bg);
            $export = $this->makeExportJson($situation = "OK" ,$message ="عکس در بانک ذخیره شد",$data = "$filePath");
            return $export;
        }else{
            imagedestroy($bg);
        }
      
    }

    // ساخت خروجی برای نمایش بصورت جیسون
    public function makeExportJson($situation = true ,$message ="اتصال به بانک برقرار است",$data = ""){
        $myarray = ['situation'=> $situation ,'message'=> $message,'data'=> $data];
        return $myarray;
    }


    /*
        آدرس صفحه را باز میگرداند
    */
    public function getPageUrl(){
       $url = trim(strval($_SERVER['REQUEST_URI']));
        //    dd($url[0]);
        $newurl=[];
        if(strripos($url,"/") !=0){
        $url = explode("/",$url);
        foreach($url as $item){
            if($item != ""){
                array_push($newurl,$item);
            }
        }
       }
       return $newurl;
    }

    
    /*
      بخش های آدرس صفخه
    */
    public function urlpartCounts(){
       $url = $this->getPageUrl();
       if(is_array($url)){
        $count = count($url);  
       }else{
        $count = 0;
       }
       return  $count;
    }
  
    // آیا درخواست از سمت Api  است
    public function isapi(){
       if($this->urlpartCounts() !=0){
        $urlFirstpart = $this->getPageUrl()[1];
        if($urlFirstpart =="api"){
            return true;
        }else{
            return false;
        }
        }else{
            return false;
        }

    }

    public function saveMediaFile($request,$photo,$photoname,$photoAddress){
        $photo = "";
        $photo = $request[$photo];
        $thedata = $this->checkConnection();
        $photoname = $this->imagefilename();
        $thefile = $photoAddress.$photoname.".png";
        $theTextFile  =  $photoAddress.$photoname.".txt";
        if($photo != ""){ 
            $fullAddress           = $photoAddress.$photoname.".png";
            if($myfile = fopen($thefile, "w")){
                $thedata["message"] = "عکس در آدرس".$fullAddress."ذخیره شد"."-";
            }
            fclose($myfile);
            $photo= $this->base64_to_jpeg($photo,$photoAddress,$photoname);
        }
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Extras $extras)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Extras $extras)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Extras $extras)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Extras $extras)
    {
        //
    }
}
