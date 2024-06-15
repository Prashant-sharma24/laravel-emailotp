<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>login</title>
</head>
<body>
@if ($errors->any())
<div>
    <ul>
        @foreach ($errors->all() as $error )

        <li>{{ $error }}</li>

        @endforeach
    </ul>
</div>
@endif

    <form action="{{ route('send.otp')}}" method="POST">
@csrf
<label for="email">Email</label>
<input type="email" id="email" name="email" required><br><br>
<button type="submit">Send Otp</button>

    </form>
</body>
</html>
