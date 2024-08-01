<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$senderName}} invite you to Treelo board</title>
</head>
<body>
<p style="font-weight: bold;font-size: 30px">Treelo</p>
<p style="font-weight: bold">{{$senderName}} mời bạn vào bảng {{$boardName}} trong ứng dụng web Treelo</p>
<a style="display: inline-block;padding: 10px;background-color: #3E95FF;border-radius:12px;color: white;text-decoration: none;font-weight: 600"
   href="http://localhost:3000/pre-login/board/{{$boardID}}?email_receiver={{$emailReceiver}}">Go to board</a>
</body>
</html>
