<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CSJ - @yield('title')</title>
    <link rel="shortcut icon" href="/img/favicon/avatar2.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap");
    </style>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
  </head>
  <body id="body-pd">
    <header class="header" id="header">
      <div class="header_toggle">
        <i class="bx bx-menu" id="header-toggle"></i>
      </div>
      <div class="header_img">
        <img src="https://i.imgur.com/hczKIze.jpg" alt="" />
      </div>
    </header>
    <div class="l-navbar" id="nav-bar">
      <nav class="nav">
        <div>
          <a href="#" class="nav_logo">
            <i class="bx bx-layer nav_logo-icon"></i>
            <span class="nav_logo-name">BBBootstrap</span>
          </a>
          <div class="nav_list">
            <a href="#" class="nav_link active">
              <i class="bx bx-grid-alt nav_icon"></i>
              <span class="nav_name">Dashboard</span>
            </a>
            <a href="#" class="nav_link">
              <i data-feather="file" class="nav_icon"></i>
              <span class="nav_name">{{ __('Orders') }}</span>
            </a>
            <!--
            <hr style="width: calc(100% + 1em); color:white;">
            -->
            <hr style="margin-left:1rem; color:white;">
            <a href="#" class="nav_link">
              <i data-feather="users" class="nav_icon"></i>
              <span class="nav_name">Users</span>
            </a>
            <a href="#" class="nav_link">
              <i class="bx bx-message-square-detail nav_icon"></i>
              <span class="nav_name">Messages</span>
            </a>
            <a href="#" class="nav_link">
              <i class="bx bx-bookmark nav_icon"></i>
              <span class="nav_name">Bookmark</span>
            </a>
            <a href="#" class="nav_link">
              <i class="bx bx-folder nav_icon"></i>
              <span class="nav_name">Files</span>
            </a>
            <a href="#" class="nav_link">
              <i class="bx bx-bar-chart-alt-2 nav_icon"></i>
              <span class="nav_name">Stats</span>
            </a>
          </div>
        </div>
        <a href="#" class="nav_link">
          <i class="bx bx-log-out nav_icon"></i>
          <span class="nav_name">SignOut</span>
        </a>
      </nav>
    </div>
    <div class="height-100 bg-light">
     @if(Session::has('success'))
     <div class="row">
        <div class="alert alert-success">
            {{ Session::get('success') }}
            @php
                Session::forget('success');
            @endphp
        </div>
     </div>
     @endif
     @if (count($errors) > 0)
     <!--
     @php var_dump($errors) @endphp
     <div class="row">
         <div class="alert alert-danger">
             <strong>Whoops!</strong> There were some problems with your input.<br><br>
             <ul>
                 @foreach ($errors->all() as $error)
                     <li>{{ $error }}</li>
                 @endforeach
             </ul>
         </div>
     </div>
     -->
     @endif
     <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">@yield('title', 'CSJ')</h2>
        <div class="btn-toolbar mb-2 mb-md-0">
          @yield('btn-toolbar')
        </div>
      </div>
      @yield('content')
    </div>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
      crossorigin="anonymous"
    ></script>
    <script src='{{ asset("js/bootstrap.bundle.min.js") }}'></script>
    <script src='{{ asset("js/feather.min.js") }}'></script>
    <script src='{{ asset("js/chart.min.js") }}'></script>
    <script src='{{ asset("/js/dashboard.js") }}'></script>
    <script type="text/javascript">
      document.addEventListener("DOMContentLoaded", function (event) {
        const showNavbar = (toggleId, navId, bodyId, headerId) => {
          const toggle = document.getElementById(toggleId),
            nav = document.getElementById(navId),
            bodypd = document.getElementById(bodyId),
            headerpd = document.getElementById(headerId);

          // Validate that all variables exist
          if (toggle && nav && bodypd && headerpd) {
            toggle.addEventListener("click", () => {
              // show navbar
              nav.classList.toggle("show");
              // change icon
              toggle.classList.toggle("bx-x");
              // add padding to body
              bodypd.classList.toggle("body-pd");
              // add padding to header
              headerpd.classList.toggle("body-pd");
            });
          }
        };

        showNavbar("header-toggle", "nav-bar", "body-pd", "header");

        /*===== LINK ACTIVE =====*/
        const linkColor = document.querySelectorAll(".nav_link");

        function colorLink() {
          if (linkColor) {
            linkColor.forEach((l) => l.classList.remove("active"));
            this.classList.add("active");
          }
        }
        linkColor.forEach((l) => l.addEventListener("click", colorLink));

        // Your code to run since DOM is loaded and ready
      });
    </script>
  </body>
</html>

