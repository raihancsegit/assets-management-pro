<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Assets Management Pro</title>
    </head>
    <body>
        <center>
          <h1>Assets Management Pro</h1>
          <h2>Contact with us</h2>
          <h4>sagor.touch@gmail.com</h4>
        
          @if (Route::has('business-login'))
            <div>
                @auth
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                @else
                    <a href="{{ route('business-login') }}">Log in</a>
                @endauth
            </div>
          @endif
        </center>
    </body>
</html>
