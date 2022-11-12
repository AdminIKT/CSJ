<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>    
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CSJ - @yield('title')</title>
    <link rel="shortcut icon" href="/img/favicon/avatar2.png" type="image/x-icon" />  
    <!--<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>-->
  <!--  <style>
      @font-face {
        font-family: 'Roboto';
        src: url('fonts/Roboto-Regular.ttf');
      }
    </style>-->
   
  </head>
  <body id="body-pd" style="font-size:18px; color:#2b2f60; font-family: sans-serif;">
      <img style="width:200px;" src="img/logo/logo.png"/>
      <br/>
      <br/>
      <br/>
      <div style="margin-left:30px; margin-right:30px;">
        @yield('content')
      </div>    
  </body>
</html>

