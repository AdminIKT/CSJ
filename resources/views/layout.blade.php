<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <title>SJ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        a {
            text-decoration: none;
        }
        #layout-container {
            background-color: white;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<!-- As a heading -->
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#">
      <img src="/img/bootstrap-logo.svg" alt="" width="30" height="24" class="d-inline-block align-text-top">
    </a>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
      @auth
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link {{request()->is('') ? 'active' : ''}}" aria-current="page" href="/">{{ Auth::user()->getEmail() }}</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{request()->is('accounts*', 'areas*') ? 'active' : ''}}" href="{{ route('accounts.index') }}">{{ __('Settings') }}</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{request()->is('orders*') ? 'active' : ''}}" href="{{ route('orders.index') }}">{{ __('Orders') }}</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{request()->is('orderCharges*') ? 'active' : ''}}" href="{{ route('orderCharges.index') }}">{{ __('OrderCharges') }}</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{request()->is('suppliers*') ? 'active' : ''}}" href="{{ route('suppliers.index') }}">{{ __('Suppliers') }}</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{request()->is('auth*') ? 'active' : ''}}" href="{{ route('logout') }}">{{ __('Logout') }}</a>
        </li>
        
        </ul>
        @endauth
        @guest
        <!--
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link {{request()->is('auth*') ? 'active' : ''}}" href="{{ route('auth') }}">Auth</a>
        </li>
        -->
        @endguest
     <!--
      <form class="d-flex">
        <input class="form-control form-control-sm me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-sm btn-outline-success" type="submit">Search</button>
      </form>
    -->
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        @foreach(config('app.available_locales') as $localeName => $availableLocale)
        <li class="nav-item">
          <a class="nav-link {{ ($availableLocale == app()->getLocale()) ? 'disabled' : '' }}" aria-current="page" href="language/{{ $availableLocale }}">{{ $localeName }}</a>
        </li>
        @endforeach
      </ul>
    </div>
  </div>
</nav>

<div class="container py-2" id="layout-container">
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
     @php //var_dump($errors) @endphp
        <!--
         <div class="alert alert-danger">
             <strong>Whoops!</strong> There were some problems with your input.<br><br>
             <ul>
                 @foreach ($errors->all() as $error)
                     <li>{{ $error }}</li>
                 @endforeach
             </ul>
         </div>
        -->
     @endif

     <div class="row">
        <div class="col-12 my-2">
            <h2>@yield('pageTitle')</h2>
        </div>
        <div class="col border-end pb-4">
            @yield('sidebar')
        </div>
        <div class="col-10">
            @yield('content')
        </div>
      </div>

    <!--
    <ul>
     @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
     @endforeach
    </ul>
    -->

</div>
<div class="p-5">
    Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
</div>
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
