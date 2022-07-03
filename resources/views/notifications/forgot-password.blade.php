<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@600&display=swap" rel="stylesheet">

<body style="background-color: #0D0B14;">
<div style="color: white; font-size:20px;">
    <div style="text-align:center; margin-top:100px;">
        <img src="{{asset('images/quote.png')}}" alt="img">
        <p style="color:#DDCCAA;">Movie Quotes</p>
    </div>
    <div style="margin-top:180px; margin-left: 100px">
        <p>Hola {{$passwordReset->email}}</p>
        <p style="margin-top: 40px; margin-bottom:60px;">Forgot your password?</p>
        <a href="http://localhost:3000/reset-password/{{$passwordReset->token}}/{{$passwordReset->email}}/" style="text-decoration:none; color:white; padding: 10px 20px; background-color: #E31221;">Password Reset</a>
        <p style="margin-top:60px; margin-bottom:50px">If clicking doesn't work, you can try copying and pasting it to your browser:</p>
        <a style="margin-top:20px; color:#DDCCAA;" href="http://localhost:3000/reset-password/{{$passwordReset->token}}/{{$passwordReset->email}}/">http://localhost:3000/reset-password/{{$passwordReset->token}}/{{$passwordReset->email}}</a>
        <p style="margin-top:50px">If you have any problems, please contact us: support@moviequotes.ge</p>
        <p style="margin-top:35px;">MovieQuotes Crew</p>
    </div>
</div>
</body>
