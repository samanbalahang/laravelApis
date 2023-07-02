<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCat;
use App\Models\PostsType;
use App\Models\postComments;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ExtrasController;


use function PHPUnit\Framework\isEmpty;

/*------------------------------
* روت پست ها این روت میتواند 
*تمام پست های کاربر را هندل کند
* برای اجرای پست های اخبار
* وبلاگ و غیره با کمک دسته بندی 
* پست میتوان از این کنترلر استفاده کرد
*
*---------------------------------*/
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function isDbconected($request = "",$uri = ""){
        $extrasController = new ExtrasController;
        if($extrasController ->isDatabaseConected() == "OK" ){
            return "OK";
        }else{
            return json_encode($extrasController -> checkConnection(), JSON_UNESCAPED_UNICODE);
        }
    }

    public function whichuri(){
        // براساس نام روت
        // $routeName =substr($request->route()->getName(),(strpos($request->route()->getName(), '.') + 1));
        // dd($request->route()->getName());
        // if($routeName == "index")
        // $routeName = "posts";
        // return $routeName ;
        // براساس url

        $extrasController = new ExtrasController;
        $url = $extrasController->getPageUrl();
        $countarray = $extrasController->urlpartCounts();
        $isapi = $extrasController->isapi();
        if($isapi){
            if($url[2] == "news"){
                return "news";
            }elseif($url[2] == "article"){
                return "article";
            }else{
                 return "posts";
            }
        }else{
            if($url[0] == "news"){
                return "news";
            }elseif($url[0] == "article"){
                return "article";
            }else{
                 return "posts";
            }
        }
    }

    public function posttype($request,$uri){
        // براساس آدرس صفحه
       $fulluri = $_SERVER["REQUEST_URI"];
       $fulluri = urldecode($fulluri);
       if(strpos($fulluri,"/") == 0){
        $fulluri = substr($fulluri,1);
       }
       if(strpos($fulluri,"/") != false || strpos($fulluri,"/") ==0){
            // dd(strpos($fulluri,"/"));
            if(strpos($fulluri,"laravel") != false || strpos($fulluri,"laravel") == 0){
                $posttype = substr($fulluri,8);
                if(strpos($posttype,"api") != false || strpos($posttype,"api") == 0){
                    $posttype = substr($posttype,4);
                }
                if(substr($posttype,0,1) != "/"){
                    if(strpos($posttype,"/") !=false){
                        $posttype = substr($posttype,0,strpos($posttype,"/"));
                    }
                    if($posttype != "news" && $posttype != "article"){
                        $posttype = "posts";
                    }
                }else{
                    if($posttype != "news" && $posttype != "article"){
                        $posttype = "posts";
                    }
                }
                // $posttype = substr($fulluri,8,strpos($fulluri,"/"));          
            }else{
                $posttype = substr($fulluri,0,strpos($fulluri,"/"));  
            }
       }else{
            $posttype = $fulluri;
       }

       return $posttype;
    }

    public function theposturi(Request $request,$uri){
        // براساس آدرس صفحه
        $fulluri = $_SERVER["REQUEST_URI"];
        $posttype =$this->posttype($request,$uri);
        $posttypeLength = strlen($posttype);
        $pageuri = substr($fulluri,$posttypeLength);
        if(strpos($pageuri,"/") == 0){
             $fulluri = substr($fulluri,1);
        }
        // $pageuri = substr($fulluri,strpos($fulluri,"/"));
        $pageuri = substr($pageuri,strpos($pageuri,"/"));
        if(substr($pageuri,0,1) == "/"){
            $pageuri = substr($pageuri,1);
        }
        return $pageuri;
    }

    public function getconnection($request= "",$uri = ""){
        // یک متغیر پایه دیتا براساس اتصال پس میدهد جیسون خواهد شد.
        $extrasController = new ExtrasController;
        $thedata = $extrasController->checkConnection(); 
        return $thedata;
    }
    
    public function index(Request $request,$uri = "")
    {

        $extrasController = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection($request);
        $routeName = $this->whichuri();
        if($this->isDbconected($request,$uri) == "OK"){
            $typeName = PostsType::where('postTypeEnName', $routeName)->first();
            $typeNameId = $typeName->id;
            
            if($typeName != null){
                $post = Post::where("postTypeId",$typeNameId)->get();
                if(!$post->isEmpty()){
                    $datajson = json_encode($post, JSON_UNESCAPED_UNICODE);
                    $thedata["data"] = $datajson;
                }else{
                    $thedata["situation"] = "False";
                    $thedata["message"] = "پستی برای". $routeName . " پیدا نشد";
                }
            }else{
                    $thedata["situation"] = "False";
                    $thedata["message"] = "جدول نوع پست خالی است posts_types";
            }
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }
        return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        // اسم روت را گرفته و اگر دسته بندی با آن نام وجود داشت به اپ بر میگردانیم.
        // روت های اخبار و مقالات با کمک دسته بندی پست ها فراخوانی میشوند.
       
        
    }
  
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        echo " create";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return "fd";
        // if(!file_exists("../uploads/test/")){
        //     mkdir("../uploads/test/", 0700);
        // }
        

        //
        $extrasController = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection($request);
        $photo = "";
        if(isset($request->photo)){
            $photo = $request->photo;
        }
        $photoAddress = "../uploads/post/";
        $photoname    =  $extrasController->imagefilename();
        $thefile      =  $photoAddress.$photoname.".png";
        $theTextFile  =  $photoAddress.$photoname.".txt"; 
        // if($myfile = fopen("../uploads/newfile.txt", "w")){
        //     // echo "hi ";
        // }
        // $txt = "John Doe\n";
        // fwrite($myfile, $txt);
        // $txt = "Jane Doe\n";
        // fwrite($myfile, $txt);
        // fclose($myfile);
        // if($myfile = fopen($theTextFile, "w")){
        //     echo "hi ";
        // }
        // $txt = "John Doe\n";
        // fwrite($myfile, $txt);
        // $txt = "Jane Doe\n";
        // fwrite($myfile, $txt);
        // fclose($myfile);


        if($photo != ""){    
            // return $photoAddress;
           
            $fullAddress           = $photoAddress.$photoname.".png";
            if($myfile = fopen($thefile, "w")){
                $thedata["message"] = "عکس در آدرس".$fullAddress."ذخیره شد"."-";
            }
            // $myfile = fopen($fullAddress , "w") or die("Unable to open file!");
            fclose($myfile);
            $photo= $extrasController->base64_to_jpeg($photo,$photoAddress,$photoname);
            // return "hi";
           
            // return '/'.$photoAddress.$photoname.'jpg';
            // return "hi";
        }

 

        if($this->isDbconected($request) == "OK"){
            $unsetMediaReq = $request;
            unset($unsetMediaReq["photo"]);

            $newreq = $extrasController->jsonRequestToObj($unsetMediaReq);
            // return $newreq;
            // return "hi" . $newreq->postTypeId;
            if($newreq){              

                $savedAddress = "/uploads/post/";
          
                // return $photoAddress;
                $objectOfRequesr =  $newreq;
                $newPost = new Post;
                $newPost->postTypeId     =  $objectOfRequesr->postTypeId;
                $newPost->collection_id  =  $objectOfRequesr->collection_id;
                $newPost->uri            =  $objectOfRequesr->uri;
                $newPost->title          =  $objectOfRequesr->title;
                $newPost->description    =  $objectOfRequesr->description;
                $newPost->content        =  $objectOfRequesr->content;
                $newPost->photo          =  $savedAddress.$photoname.'.jpg';
                $newPost->galleryId      =  $objectOfRequesr->galleryId;
                $newPost->likes          =  $objectOfRequesr->likes;
                $newPost->views          =  $objectOfRequesr->views;              
                if($newPost->save()){
                    $thedata["situation"] = "OK";
                    $thedata["message"] = $thedata["message"]."با موفقیت در بانک ذخیره شد";
                    $thedata["data"] = $newPost;
                    return json_encode($thedata, JSON_UNESCAPED_UNICODE);
                };
            }
        }else{
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request ,Post $post,$uri ="")
    {
        $routeName = $this->whichuri($request,$uri);
            

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
        echo " edit";
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post,$url)
    {
        $extrasController = new ExtrasController;
        $thedata = $this->getconnection();
        $unsetMediaReq = $request;
        unset($unsetMediaReq["photo"]);
        $newreq = $extrasController->jsonRequestToObj($unsetMediaReq);
        $savedAddress = "/uploads/post/";
        $postTypeId = $newreq->postTypeId;
        $thePost= Post::where("uri",$url)->where("postTypeId",$postTypeId)->first();
        if($thePost->count() != 0){
            if($newreq->likes > 0){
                $thePost->likes = $thePost->likes + intval($newreq->likes);
            }else{
                $thePost->likes = $thePost->likes;
            }
            if($newreq->views > 0){
                $thePost->views = $thePost->views + intval($newreq->views);
            }else{
                $thePost->views = $thePost->views;
            }
            if($newreq->postTypeId !=0){
                $thePost->postTypeId  =  $newreq->postTypeId;
            }
            $thePost->uri         =  $newreq->uri;
            if($newreq->title != ""){
                $thePost->title       = $newreq->title;
            }
            if($newreq->description != ""){
                $thePost->description =  $newreq->description;
            }
            if($newreq->content != ""){
                $thePost->content     =  $newreq->content;
            }
            if($newreq->galleryId != 0){
                $thePost->galleryId   =  $newreq->galleryId;
            }
            // if($thePost->photo != $request->photo){
            //     $photoname    =  $extrasController->imagefilename();
            //     $extrasController->saveMediaFile($request,$thePost->photo,$photoname,"../uploads/post/");
            //     $thePost->photo  = $savedAddress.$photoname.'.jpg';
            // }
            if($thePost->save()){
                $thedata["situation"] = "OK";
                $thedata["message"] = $thedata["message"]."با موفقیت در بانک ذخیره شد";
                $thedata["data"] = $thePost;
                return json_encode($thedata, JSON_UNESCAPED_UNICODE);
            }           
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);

            
           
        }
        //
        echo " update";
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
        echo " destroy";
    }

    public function postUri(Request $request,$uri = "")
    {
        // نوع پست یا پست است یا خبر است یا مقاله است
        $posttype = $this->posttype($request,$uri);
        
        //آدرس کامل صفحه درخواست شده 
        // $posturl = urldecode($this->theposturi($request,$uri));
        $posturl = $uri;
        // dd($uri);
        // dd($posttype);
        // dd($posturl);
        // dd( $posttype != $posturl);
        $extrasController = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection($request);
        if($this->isDbconected($request,$uri) == "OK"){
    
            // اکر کانکشن بود 
            $typeName = PostsType::where('postTypeEnName', $posttype)->first();    
            if($typeName != null){
                // اگر نوع تایپ وجود داشت 
                $typeNameId = $typeName->id;
      
                if($posttype != $posturl){
                    // اگر درخواست یک پست داده شده بود
                    $posts = Post::where("postTypeId",$typeNameId)->where("uri",$posturl)->first();
                
                    if($posts != null){
                        $postId = $posts->id;
                        $thepostComments =new postComments;
                        $allpostComments =  $thepostComments::where("post_id",$postId)->get();
                        // dd($allpostComments->first());
                        // اگر چنین پستی وجود دارد
                        $expostComments = [];
                        $usersCommentSender = [];
                        // $allpostComments = $posts->comments;
                        
                        foreach ($allpostComments as $postComment) {
                            if (UserProfile::exists()){ 
                                $users = UserProfile::find($postComment->user_profile_id);
                                if(isset($users)) {
                                    if(!isset($postComment["user"])){
                                        $postComment["user"]=[];
                                    }
                                    // اطلاعات کاربری که کامنت داده را بر میگردانیم
                                    $postComment["user"]=[$users->id,$users->fName,$users->lName,$users->fullName,$users->profilePhoto];
                                    // array_push($postComment["user"],$users->id,$users->fName,$users->lName,$users->profilePhoto);
                                    // dd($postComment["user"]);
                                    // array_push($postComment["user"],$users->id,$users->fName,$users->lName,$users->profilePhoto);
                                    // dd(array_push($postComment["user"],$users->id,$users->fName,$users->lName,$users->profilePhoto));
                                    // $postComment["user"] = $users;
                                    // array_push($usersCommentSender ,$users);
                                  
                                }else{
                                    $postComment["user"]=[];
                                    $usersCommentSender[]="";
                                }
                            }else{
                                $postComment["user"]=[];
                                $usersCommentSender[]="";
                            }
                          
                            array_push($expostComments,$postComment);
                          }
                   
                        // $export = [$posts,"comments"=>$allpostComments];
                        $export = [$posts,"comments"=>$expostComments];
                        // $datajson = json_encode($posts, JSON_UNESCAPED_UNICODE);
                        $datajson = json_encode($export, JSON_UNESCAPED_UNICODE);
                        $thedata["data"] = $datajson;
                    }
                    else{
                        // پست وجود ندارد
                        $thedata["situation"] = "False";
                        $thedata["message"] = "پست مد نظر یافت نشد";
                        abort(404,json_encode($thedata, JSON_UNESCAPED_UNICODE));
                    }
                }else{
               
                    // اگر درخواست چند پست داده شده بود.
                    $post = Post::where("postTypeId",$typeNameId)->get();
                  

                    if(!$post->isEmpty()){
             
                        $datajson = json_encode($post, JSON_UNESCAPED_UNICODE);
                        $thedata["data"] = $datajson;
                        $thedata["message"] = "داده یافت شد.";
                        return json_encode($thedata, JSON_UNESCAPED_UNICODE);
                    }
                }
            }
            else{
                // نوع پست وجود ندارد
                $thedata["situation"] = "False";
                $thedata["message"] = "دسته بندی مد نظر یافت نشد";
                abort(404,json_encode($thedata, JSON_UNESCAPED_UNICODE));
            } 
        }
        else{
            // کانکشن برقرار نیست 
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }   

        return json_encode($thedata, JSON_UNESCAPED_UNICODE);
    }

    public function newsArticle()
    {

        $extrasController = new ExtrasController;
        // چک کردن کانکشن به بانک
        $thedata = $this->getconnection();
        if($this->isDbconected() == "OK"){
            $typeNameId = 2;
            $post = Post::where("postTypeId",$typeNameId)->get();
            if(!$post->isEmpty()){
                    $datajson["news"] = json_encode($post, JSON_UNESCAPED_UNICODE);
                    $thedata["data"] = $datajson;

            }else{
                    $thedata["situation"] = "False";
                    $thedata["message"] = "اخبار خالی است";
            }
            $typeNameId2 = 3;
            $article = Post::where("postTypeId",$typeNameId2)->get();
            if(!$article->isEmpty()){
                    $datajson["article"] = json_encode($article, JSON_UNESCAPED_UNICODE);
                   
                    $thedata["data"] = $datajson;
            }else{
                    $thedata["situation"] = "False";
                    $thedata["message"] = "مقالات خالی است";
            }
            return json_encode($thedata, JSON_UNESCAPED_UNICODE);
        }
    }
}
