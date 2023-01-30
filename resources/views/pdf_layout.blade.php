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
  <body id="body-pd" style="font-size:14px; color:#2b2f60; font-family: sans-serif;">
      <table width="100%">
        <tr>
            <td><img style="width:220px;" src="img/logo/logo.png"/></td>
            <td style="font-size:12px;text-align:center"><b>CIFP SAN JORGE LHII</b><br/>
            C/Pajares 34, 48980 Santurtzi (Bizkaia)<br/>
            Tlf.: 944004930 Fax: 944839060<br/>
            Web: <u>www.fpsanjorge.com</u><br/>
            Email: <u>sanjorge@fpsanjorge.com</u><br/>
            <b>CIF: S4833001C</b>
            </td>
            <td><img style="width:200px;" src="img/logo/logo_aenor.png"/></td>
        </tr>
      </table>    
      <br/>
      <br/>
      <div style="margin-left:30px; margin-right:30px;">
        @yield('content')
      </div>    
  </body>
</html>

