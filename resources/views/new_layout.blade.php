<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <title>SJ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="img/favicon/avatar2.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="/css/dashboard.css" rel="stylesheet">
    <style>
.gb_ka {
    display: inline-block;
    padding-bottom: 2px;
    padding-left: 7px;
    padding-top: 2px;
    text-align: center;
    vertical-align: middle;
    line-height: 32px;
    /*width: 78px;*/
}
#sidebarMenu .nav-link:hover {
    background: #DDD;
}
    </style>
</head>
<body>

<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="/">
    <!--<img src="/img/logo/logo.png" alt="" class="gb_ka">-->
    <!--<img src="/img/favicon/avatar3.png" alt="" class="gb_ka">-->
  </a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <input class="form-control form-control-dark w-100 py-0 rounded" type="text" placeholder="Search" aria-label="Search">
  <div class="navbar-nav">
    <div class="nav-item text-nowrap">
      <a class="nav-link px-3" href="{{ route('logout') }}">{{ __('Logout') }}</a>
    </div>
  </div>
  
</header>

<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3">
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <!--<span>{{ __('Dashboard') }}</span>-->
          <span>{{ Auth::user()->getEmail() }}</span>
        </h6>
        <ul class="nav flex-column">
          <!--
          <li class="nav-item">
            <a class="nav-link {{request()->is('') ? 'active' : ''}}" aria-current="page" href="/">
              <span data-feather="home"></span>
              {{ __('Dashboard') }}
            </a>
          </li>
          -->
          @foreach (Auth::user()->getAreas() as $area)
          <li class="nav-item">
            <a class="nav-link" href="{{ route('areas.show', ['area' => $area->getId()]) }}">
            {{ $area->getName() }} ({{$area->getTypeName() }})
            </a>
          </li>
          @endforeach
        </ul>
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>Lists</span>
          <a class="link-secondary" href="#" aria-label="Add a new report">
            <span data-feather="plus-circle"></span>
          </a>
        </h6>
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link {{request()->is('orders*') ? 'active' : ''}}" href="{{ route('orders.index') }}">
              <span data-feather="file"></span>
              {{ __('Orders') }}
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{request()->is('movements*') ? 'active' : ''}}" href="{{ route('movements.index') }}">
              <span data-feather="shopping-cart"></span>
              {{ __('Movements') }}
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{request()->is('suppliers*') ? 'active' : ''}}" href="{{ route('suppliers.index') }}">
              <span data-feather="shopping-bag"></span>
              {{ __('Suppliers') }}
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="bar-chart-2"></span>
              Reports
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="layers"></span>
              Receptions 
            </a>
          </li>
        </ul>
        <!--<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>Reports</span>
          <a class="link-secondary" href="#" aria-label="Add a new report">
            <span data-feather="plus-circle"></span>
          </a>
        </h6>
        <ul class="nav flex-column mb-2">
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="file-text"></span>
              Current week 
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="file-text"></span>
              Current month
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="file-text"></span>
              Year-end sale
            </a>
          </li>
        </ul>-->
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>{{ __('Settings') }}</span>
          <a class="link-secondary" href="#" aria-label="Add a new report">
            <span data-feather="plus-circle"></span>
          </a>
        </h6>
        <ul class="nav flex-column mb-2">
          <li class="nav-item">
            <a class="nav-link {{request()->is('users*') ? 'active' : ''}}" href="{{ route('users.index') }}">
              <span data-feather="users"></span>
              {{ __('Users') }}
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{request()->is('departments*') ? 'active' : ''}}" href="{{ route('departments.index') }}">
              <span data-feather="hexagon"></span>
              {{ __('Departments') }}
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{request()->is('areas*') ? 'active' : ''}}" href="{{ route('areas.index') }}">
              <span data-feather="globe"></span>
              {{ __('Areas') }}
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="settings"></span>
              {{ __('Severals') }}
            </a>
          </li>
        </ul>
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>{{ __('Languajes') }}</span>
          <a class="link-secondary" href="#" aria-label="Add a new report">
            <span data-feather="plus-circle"></span>
          </a>
        </h6>
        <ul class="nav flex-column mb-2">
          @foreach(config('app.available_locales') as $localeName => $availableLocale)
          <li class="nav-item">
            <a class="nav-link {{ ($availableLocale == app()->getLocale()) ? 'active' : '' }}" href="/language/{{ $availableLocale }}">
              <span></span>
              {{ $localeName }}
            </a>
          </li>
          @endforeach
        </ul>
      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
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
        <h1 class="h2">@yield('title', 'SJ')</h2>
        <div class="btn-toolbar mb-2 mb-md-0">
          @yield('btn-toolbar')
        </div>
      </div>
      @yield('content')
      <!--
      <div class="px-0 py-5">
          Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
      </div>
      -->
    </main>
  </div>
</div>


    <!--<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>-->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
      <script src="/js/dashboard.js"></script>
      <script>
      // Example starter JavaScript for disabling form submissions if there are invalid fields
      (function () {
        'use strict'
      
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')
      
        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
          .forEach(function (form) {
            form.addEventListener('submit', function (event) {
              if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
              }
      
              form.classList.add('was-validated')
            }, false)
          })
      })()
      </script>
      @yield('scripts')
  </body>
</html>
