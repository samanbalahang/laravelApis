<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
</head>
<body>
    <form action="{{route('user.getPhone')}}" method="POST">
        @csrf
        @method('POST')
        @if(isset($data))
        <label for="text">شماره تلفن</label>
        <input type="text" name="phoneNumber" value="{{$phoneNumber}}"><br>
        <label for="fourDegits">کد چهار رقمی تایید</label>
        <input type="text" name="fourDegits" value="{{$data}}"><br>
        <label for="checkhash">هش کاربر</label>
        <input type="text" name="checkhash" value="{{$checkhash}}"><br>
        @else
        <label for="text">شماره تلفن</label>
        <input type="text" name="phoneNumber"><br>
        @endif
        
        <input type="submit" value="درج">
    </form>
    <hr>
    <form action="" method="POST" id="senAjaz">
        <label for="postTypeId">postTypeId</label>
        <input type="text" name="postTypeId"><br>
        <label for="uri">uri</label>
        <input type="text" name="uri"><br>
        <label for="title">title</label>
        <input type="text" name="title"><br>
        <label for="description">description</label>
        <input type="text" name="description"><br>
        <label for="content">content</label>
        <input type="text" name="content"><br>
        <label for="galleryId">galleryId</label>
        <input type="text" name="galleryId"><br>
        <input type="submit">
    </form>
    <script src="{{url('/')}}/assets/js/jquery-3.6.0.min.js"></script>
    <script>
        $("#senAjaz").on("submit",function(e){
            e.preventDefault();
            const formElement = document.getElementById("senAjaz");
            var a = new FormData(formElement);
            // var formData = JSON.stringify($("#senAjaz").serializeArray());
            // console.log($(this).serialize());
            // console.log(new FormData(document.getElementById("senAjaz")));
            var object = {};
            a.forEach(function(value, key){
                object[key] = value;
            });
            var json = JSON.stringify(object);
            // console.log(json);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
            $.ajax({
                url: "<?php echo route("post.store") ?>",
                type: 'POST',
                data: json,
                success: function(response)
                {
                    console.log(response);
                }
            });
            e.preventDefault();
        });
        
    </script>
</body>
</html>