<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <title>CSJ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/img/favicon/avatar2.png" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @yield('styles')
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
header {
    background:#70c4bd;
}
#sidebarMenu .nav-link:hover {
    background: rgb(112 196 189/ 10%)
}
#sidebarMenu .nav-link.active {
    background: rgb(112 196 189/ 25%)
}
th a.active {
    color: lightblue;
}
    </style>
</head>
<body>

<header class="navbar navbar-light bg-light sticky-top flex-md-nowrap p-0 shadow">
  <!--
  <a class="navbar-brand col-md-3 col-lg-2 text-center p-0" href="/">
    <img src="/img/favicon/avatar3.png" alt="" class="gb_ka">
  </a>
  -->
  <div class="navbar-brand col-md-3 col-lg-2 text-center p-0">
      <form action="{{ route('settings.update', ['setting' => $currentYear->getId()]) }}" 
          method="POST" 
          class="row" 
          novalidate>
      @csrf
      {{ method_field('PUT') }}
      <div class="input-group input-group-sm">
        <span class="input-group-text" id="basic-addon1">{{ __('Current year') }}</span>
        {{ Form::select('value', $currentYearOptions, old('value', $currentYear->getValue()), ['id' => 'currentYear', 'class'=>'form-select form-select-sm rounded-0', 'onchange' => 'this.form.submit()']) }}
      </div>
      {{ Form::hidden('destination', request()->url()) }}
      </form>
  </div>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <input class="form-control form-control-dark w-100 py-0 rounded" type="text" placeholder="Search" aria-label="Search">
  <div class="navbar-nav">
    <div class="nav-item text-nowrap">
      <a class="nav-link px-3 {{ app()->getLocale() == 'eus' ? 'active':'' }}" href="/language/eus" style="display:inline">eus</a>
      <a class="nav-link px-3 {{ app()->getLocale() == 'es' ? 'active':'' }}" href="/language/es" style="display:inline">es</a>
    </div>
  </div>
  
</header>

<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3">
        <div class="text-center">
            <div class="btn-group bg-white">
              <a href="/" class="btn btn-sm btn-outline-primary">
                <img src="{{ Auth::user()->getAvatar() }}" width="25" height="25" class="rounded-circle" /> {{ Auth::user()->getEmail() }}
              </a>
              <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown</span>
              </button>
              <ul class="dropdown-menu">
                @if (Auth::user()->getAccounts()->count())
                @foreach (Auth::user()->getAccounts() as $account)
                <li>
                  <a class="dropdown-item" href="{{ route('accounts.show', ['account' => $account->getId()]) }}">
                      {{ $account->getName() }} ({{$account->getTypeName() }})
                  </a>
                </li>
                @endforeach
                <li><hr class="dropdown-divider"></li>
                @endif
                <li><a class="dropdown-item" href="{{ route('logout') }}">{{ __('Logout') }}</a></li>
              </ul>
            </div>
        </div>

        @canany(['viewAny'], [App\Entities\Supplier::class, App\Entities\Movement::class, App\Entities\Order::class])
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>{{ __('Lists') }}</span>
          <!--<a class="link-secondary" href="#" aria-label="Add a new report">
            <span data-feather="plus-circle"></span>
          </a>-->
        </h6>
        <ul class="nav flex-column">
          @can('viewAny', App\Entities\Order::class)
          <li class="nav-item">
            <a class="nav-link {{request()->is('orders*') ? 'active' : ''}}" href="{{ route('orders.index') }}">
              <span data-feather="file"></span>
              {{ __('Orders') }}
            </a>
          </li>
          @endcan
          @can('viewAny', App\Entities\Movement::class)
          <li class="nav-item">
            <a class="nav-link {{request()->is('movements*') ? 'active' : ''}}" href="{{ route('movements.index') }}">
              <span data-feather="shopping-cart"></span>
              {{ __('Movements') }}
            </a>
          </li>
          @endcan
          @can('viewAny', App\Entities\Supplier::class)
          <li class="nav-item">
            <a class="nav-link {{request()->is('suppliers*') ? 'active' : ''}}" href="{{ route('suppliers.index') }}">
              <span data-feather="shopping-bag"></span>
              {{ __('Suppliers') }}
            </a>
          </li>
          @endcan
          <!--
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="bar-chart-2"></span>
              {{ __('Reports') }}
            </a>
          </li>
          -->
          @can('viewAny', App\Entities\Order::class)
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="layers"></span>
              {{ __('Receptions') }}
            </a>
          </li>
          @endcan
          <li class="nav-item">
            <a class="nav-link {{request()->is('actions*') ? 'active' : ''}}" href="{{ route('actions.index') }}">
              <span data-feather="activity"></span>
              {{ __('Activity') }}
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
        @endcan

        @canany('viewAny', [App\Entities\User::class, App\Entities\Area::class, App\Entities\Account::class])
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>{{ __('Settings') }}</span>
          <!--<a class="link-secondary" href="#" aria-label="Add a new report">
            <span data-feather="plus-circle"></span>
          </a>-->
        </h6>

        <ul class="nav flex-column mb-2">
          @can('viewAny', App\Entities\User::class)
          <li class="nav-item">
            <a class="nav-link {{request()->is('users*') ? 'active' : ''}}" href="{{ route('users.index') }}">
              <span data-feather="users"></span>
              {{ __('Users') }}
            </a>
          </li>
          @endcan
          @can('viewAny', App\Entities\Area::class)
          <li class="nav-item">
            <a class="nav-link {{request()->is('areas*') ? 'active' : ''}}" href="{{ route('areas.index') }}">
              <span data-feather="hexagon"></span>
              {{ __('Areas') }}
            </a>
          </li>
          @endcan
          @can('viewAny', App\Entities\Account::class)
          <li class="nav-item">
            <a class="nav-link {{request()->is('accounts*') ? 'active' : ''}}" href="{{ route('accounts.index') }}">
              <span data-feather="globe"></span>
              {{ __('Accounts') }}
            </a>
          </li>
          @endcan
        </ul>
        @endcan

        @canany(['viewAny'], App\Entities\Settings::class)
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>Parametros</span>
          <!--<a class="link-secondary" href="#" aria-label="Add a new report">
            <span data-feather="plus-circle"></span>
          </a>-->
        </h6>

        <ul class="nav flex-column mb-2">
          @can('viewAny', App\Entities\Settings::class)
          <li class="nav-item">
            <a class="nav-link {{request()->is('settings*') ? 'active' : ''}}" href="{{ route('settings.index') }}">
              <span data-feather="settings"></span>
              {{ __('Settings') }}
            </a>
          </li>
          @endcan
          <li class="nav-item">
          </li>
          <!--
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="settings"></span>
              {{ __('Límite facturación') }}
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="settings"></span>
              {{ __('Parametros de importación') }}
            </a>
          </li>
          -->
        </ul>
        @endcan
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
        <h1 class="h2">@yield('title', 'CSJ')</h2>
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
<!--<canvas id="myChart"></canvas>-->


      <!--<script src='{{ asset("js/popper.min.js") }}'></script>-->
      <script src='{{ asset("js/bootstrap.bundle.min.js") }}'></script>
      <script src='{{ asset("js/feather.min.js") }}'></script>
      <script src='{{ asset("js/chart.min.js") }}'></script>
      <script src='{{ asset("/js/dashboard.js") }}'></script>
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

      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
      })
      </script>
      @yield('scripts')
  </body>
</html>
