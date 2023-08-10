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

    // ذخیره فایل مدیا
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

    // فانکشن اتصال به بانک زیبال
 
    public function StartZibal(){
        /* in .env
        $merchant = "6499be0ecbbc27000c189eeb";
        */
        $merchant = env("merchant", "zibal");
        $parameters = [
            "merchant"      =>$merchant,
            "amount"        =>20000,
            "callbackUrl"   =>"https://padhanis.com/laravel/api/payment",
            "description"   =>"testpayment",
            "orderId"       =>"mytestId",
            "mobile"        =>"09224194485",
            "ledgerId"      =>"ledgerId",
            "linkToPay"     =>0,
            "sms"           =>0
        ];
        $parameters = json_encode($parameters, JSON_UNESCAPED_UNICODE);
        // dd( $parameters);
        // $parameters = "{
        //     'merchant'      :'{$merchant}',
        //     'amount'        :20000,
        //     'callbackUrl'   :'https://padhanis.com/laravel/api/payment',
        //     'description'   :'testpayment',
        //     'orderId'       :'mytestId',
        //     'mobile'        :'09224194485',
        //     'ledgerId'      :'ledgerId',
        //     'linkToPay'     :0,
        //     'sms'           :0
        // }";
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer 7ef1ad4f147c4f7e952a0e5db8f57dab',
        ];
        $url = 'https://gateway.zibal.ir/v1/request';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch , CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($parameters));
        curl_setopt($ch, CURLOPT_POSTFIELDS,$parameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response  = curl_exec($ch);
        curl_close($ch);
        return json_decode($response);
        
    }
    
    public function ZibalResult($code){
        switch ($code) {
            case 100:
                $output = "با موفقیت تایید شد.";
                break;
            case 102:
                $output = "merchantیافت نشد.";
                break;
            case 103:
                $output = "merchantغیرفعال";
                break;
            case 104:
                $output = "merchantنامعتبر";
                break;
            case 105:
                $output = "amountبایستی بزرگتر از 1,000 ریال باشد.";
                break;
            case 106:
                $output = "callbackUrlنامعتبر می‌باشد. (شروع با http و یا https)";
                break;
            case 113:
                $output = "amountمبلغ تراکنش از سقف میزان تراکنش بیشتر است.";
                break;
            case 201:
                $output = "قبلا تایید شده";
                break;
            case 202:
                $output = "سفارش پرداخت نشده یا ناموفق بوده است.";
                break;
            case 203:
                $output = "trackIdنامعتبر می‌باشد.";
                break;
            default:
                $output = "خطا در لیست نیست";
                break;
        }
        return $output;
    }

    public function ZibalPaymentStart($trackId="3252753083"){
        return "https://gateway.zibal.ir/start/".$trackId;

    }

    public function Zibalverify($merchant= "zibal",$trackId){
        // $merchant = env("merchant", "zibal");
        $url = 'https://gateway.zibal.ir/v1/verify';
        $parameters = ["merchant"=>$merchant,"trackId"=>$trackId];
        $parameters = json_encode($parameters, JSON_UNESCAPED_UNICODE);
        // $parameters = '{
        //     "merchant" : "zibal",
        //     "trackId"  :  "3252753083"    
        // }';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($parameters));
        curl_setopt($ch, CURLOPT_POSTFIELDS,$parameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response  = curl_exec($ch);
        curl_close($ch);
        return json_decode($response);


    }

    public function ZibalPaymentStatus($statusCode=3){
        switch ($statusCode) {
            case -1:
                $statusDesc = "در انتظار پردخت";
                break;
            case -2:
                $statusDesc = "	خطای داخلی";
                break;
            case 1:
                $statusDesc = "پرداخت شده - تاییدشده";
                break;
            case 2:
                $statusDesc = "پرداخت شده - تاییدنشده";
                break;
            case 3:
                $statusDesc = "لغوشده توسط کاربر";
                break;
            case 4:
                $statusDesc = "‌شماره کارت نامعتبر می‌باشد.";
                break;
            case 5:
                $statusDesc = "‌موجودی حساب کافی نمی‌باشد.";
                break;
            case 6:
                $statusDesc = "رمز واردشده اشتباه می‌باشد.";
                break;
            case 7:
                $statusDesc = "‌تعداد درخواست‌ها بیش از حد مجاز می‌باشد.";
                break;
            case 8:
                $statusDesc = "‌تعداد پرداخت اینترنتی روزانه بیش از حد مجاز می‌باشد.";
                break;
            case 9:
                $statusDesc = "مبلغ پرداخت اینترنتی روزانه بیش از حد مجاز می‌باشد.";
                break;
            case 10:
                $statusDesc = "‌صادرکننده‌ی کارت نامعتبر می‌باشد.";
                break;
            case 11:
                $statusDesc = "‌خطای سوییچ";
                break;
            case 12:
                $statusDesc ="کارت قابل دسترسی نمی‌باشد.";
                break;
            
            default:
                $statusDesc = "کد وضعیت شناسایی نشد!";
                break;
        }
        return $statusDesc;
    }



    public function postToZibal($path, $parameters)
        {
            define("ZIBAL_MERCHANT_KEY","zibal");
            define("ZIBAL_CALLBACK_URL","http://yourapiurl.com/callback.php");

            $url = 'https://gateway.zibal.ir/v1/'.$path;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($parameters));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response  = curl_exec($ch);
            curl_close($ch);
            return json_decode($response);
        }



   
        public function index()
    {
        //
    }


    
//   آپلود ساده فایل
  public function simpleFileUpload($request,$main_img="main_img",$address="uploads/posts/"){
    $file = $request->file($main_img);
    $extension = $file->getClientOriginalExtension(); // getting image extension
    $filename =$this->imagefilename(6).'.'.$extension;
    if($file->move($address, $filename)){
        return true;
    }else{
        return false;
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
