{{-- {{ $username }} --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chat Box</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/frontend_css/chat.css') }}">
</head>
<body>
    <div class="col-lg-4 col-lg-offset-4" style="margin-left: 400px">
        <h1 class="chat-header">Welcome, <span id="username">{{ $username }}</span></h1>
        <div class="chat-window col-lg-12"></div>
        <div id="typingStatus" class="col-lg-12"></div>
        <div class="col-lg-12" style="padding:15px">
            <input type="text" id="text" class="form-control col-lg-12" autofocus="" onblur="notTyping()">
        </div>
    </div>
</body>
</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="{{ asset('js/frontend_js/chat.js') }}"></script>