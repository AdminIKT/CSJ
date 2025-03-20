<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CSJ - @yield('title')</title>
    <link rel="shortcut icon" href="/img/favicon/avatar2.png" type="image/x-icon" />
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap");
    </style>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/boxicons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
  </head>
  <body id="body-pd">

    <header class="header" id="header">
      <div class="header_toggle">
        <i class="bx bx-menu" id="header-toggle"></i>
      </div>
      <h1 class="h2 my-2">@yield('title', 'CSJ')</h1>
      <div class="header_avatar">
        <a href="#" onclick="authCard()">
            <img src="{{ Auth::user()->getAvatar() }}" class="rounded-circle" style="width:100%"/>
        </a>
      </div>

      <div class="gb auth-card" style="display:none"></div>
      <div id="auth-card" class="auth-card card text-center col col-md-4 col-lg-3 overflow-auto" style="display:none">
        <div id="languajes" class="card-header">
            <div class="d-flex justify-content-between">
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-sm btn-light {{ app()->getLocale() == 'eus' ? 'disabled':'' }}" href="/language/eus">eus</a>
                    <a class="btn btn-sm btn-light {{ app()->getLocale() == 'es' ? 'disabled':'' }}" href="/language/es">es</a>
                </div>
                <a class="btn btn-sm btn-light rounded-circle shadow" href="#" onclick="authCard()">
                    <i class="bx bx-x"></i>
                </a>
            </div>
            <img src="{{ Auth::user()->getAvatar() }}" class="card-avatar rounded-circle" />
            <p class="card-subtitle my-2">{{ Auth::user()->getEmail() }}</p>
        </div>
        <div class="d-flex flex-wrap justify-content-center py-1" style="
            background:rgba(0,0,0,.03); border-bottom: 1px solid rgba(0,0,0,.125);
        ">
            <a href="https://drive.google.com" class="mx-2" target="_blank">
                <img src="/img/google/drive.png" title="Google Drive" width="20px">
            </a>
            <a href="https://gmail.google.com" class="mx-2" target="_blank">
                <img src="/img/google/gmail.png" title="Google Gmail" width="20px">
            </a>
            <a href="https://calendar.google.com" class="mx-2" target="_blank">
                <img src="/img/google/calendar.png" title="Google Calendar" width="20px">
            </a>
        </div>
        @if (Auth::user()->getAccounts()->count())
        <div class="card-body" style="max-height: 35vh; overflow-y: auto;">
            <h6 class="text-muted">
                <a href="{{ route('home') }}"><i class="bx bxs-dashboard me-1"></i>{{ __('My accounts') }}</a>
            </h6>
            @foreach (Auth::user()->getAccounts()->filter(function($item) { return $item->isActive(); }) as $account)
              <a class="dropdown-item border my-1 roundedi text-wrap" href="{{ route('accounts.show', ['account' => $account->getId()]) }}">
                  {{ $account->getSerial() }} <small>({{$account->getName()}})</small>
              </a>
            @endforeach
        </div>
        @endif
        <div class="card-footer">
            <a class="btn btn-sm" href="{{ route('logout') }}">
                <i class="bx bx-log-out"></i>
                {{ __('Logout') }}
            </a>
        </div> 
      </div>
    </header>

    <div class="l-navbar" id="nav-bar">
      <nav class="nav">
        <div style="overflow-y:scroll">
          <!--
          <a class="nav_logo {{request()->is('/') ? 'active' : ''}}" href="{{ route('home') }}" title="{{ __('Dashboard') }}">
            <i data-feather="grid" class="nav_icon"></i>
            <span class="nav_logo-name">{{ __('Dashboard') }}</span>
          </a>
          <hr style="margin-left:1rem; color:white;">
          -->
          
          <div class="nav_list">
            @if (Auth::user()->isAdmin())
                <a class="nav_link {{request()->is('admin') ? 'active' : ''}}" href="{{ route('admin') }}" title="{{ __('Control Panel') }}">
                  <i class="bx bxs-grid"></i>
                  <span class="nav_name">{{ __('Dashboard') }}</span>
                </a>
            @else
                <a class="nav_link {{request()->is('/') ? 'active' : ''}}" href="{{ route('home') }}" title="{{ __('Dashboard') }}">
                  <i class="bx bxs-dashboard"></i>
                  <span class="nav_name">{{ __('Dashboard') }}</span>
                </a>
            @endif
            <!-- ------------------------------------ -->
            <hr style="margin-left:1rem; color:white;">

            @can('viewAny', App\Entities\Order::class)
                <a class="nav_link {{preg_match('$orders*$', Request::path()) ? 'active' : ''}}" href="{{ route('orders.index') }}" title="{{ __('Orders') }}">
                  <i class="bx bx-file"></i>
                  <span class="nav_name">{{ __('Orders') }}</span>
                </a>
            @endcan
            @can('viewAny', App\Entities\Movement::class)
                <a class="nav_link {{request()->is('movements*') ? 'active' : ''}}" href="{{ route('movements.index') }}" title="{{ __('Movements') }}">
                  <i class="bx bx-dollar"></i>
                  <span class="nav_name">{{ __('Movements') }}</span>
                </a>
            @endcan
            @can('viewAny', App\Entities\Supplier::class)
                <a class="nav_link {{request()->is('suppliers*') ? 'active' : ''}}" href="{{ route('suppliers.index') }}" title="{{ __('Suppliers') }}">
                  <i class="bx bx-cart"></i>
                  <span class="nav_name">{{ __('Suppliers') }}</span>
                </a>
               
            @endcan
            @can('viewAny', App\Entities\Action::class)
                <a class="nav_link {{request()->is('actions*') ? 'active' : ''}}" href="{{ route('actions.index') }}" title="{{ __('Activity') }}">
                  <i class="bx bx-pulse"></i>
                  <span class="nav_name">{{ __('Activity') }}</span>
                </a>
            @else
                <!-- ------------------------------------ -->     
                @can('viewAny', App\Entities\Supplier::class)               
                <hr style="margin-left:1rem; color:white;">    
                @endcan       
                <a class="nav_link {{request()->is('users/*') ? 'active' : ''}}" href="{{ route('users.show', ['user' => Auth::user()->getId()]) }}" title="{{ __('My accounts') }}">
                  <i class="bx bxs-credit-card"></i>
                  <span class="nav_name">{{ __('My accounts') }}</span>
                </a>
                <a class="nav_link {{request()->is('users/*/orders') ? 'active' : ''}}" href="{{ route('users.orders.index', ['user' => Auth::user()->getId()]) }}" title="{{ __('My orders') }}">
                  <i class="bx bx-file"></i>
                  <span class="nav_name">{{ __('My orders') }}</span>
                </a>
                <a class="nav_link {{request()->is('users/*/actions') ? 'active' : ''}}" href="{{ route('users.actions.index', ['user' => Auth::user()->getId()]) }}" title="{{ __('My activity') }}">
                  <i class="bx bx-pulse"></i>
                  <span class="nav_name">{{ __('My activity') }}</span>
                </a>
            @endcan
            @can('order-receptions')
                <!-- ------------------------------------ -->
                <hr style="margin-left:1rem; color:white;">
                <a class="nav_link {{preg_match('$receptions$', Request::path()) ? 'active' : ''}}" href="{{ route('orders.receptions') }}" title="{{ __('Receptions') }}">
                  <i class="bx bxs-package"></i>
                  <span class="nav_name">{{ __('Receptions') }}</span>
                </a>
            @endcan

            @can('viewAny', App\Entities\User::class)
                <!-- ------------------------------------ -->
                <hr style="margin-left:1rem; color:white;">
                <a class="nav_link {{request()->is('users*') ? 'active' : ''}}" href="{{ route('users.index') }}" title="{{ __('Users') }}">
                  <i class="bx bx-user"></i>
                  <span class="nav_name">{{ __('Users') }}</span>
                </a>
            @endcan
            @can('viewAny', App\Entities\Area::class)
                <a class="nav_link {{request()->is('areas*') ? 'active' : ''}}" href="{{ route('areas.index') }}" title="{{ __('Areas') }}">
                  <i class="bx bx-globe"></i>
                  <span class="nav_name">{{ __('Areas') }}</span>
                </a>
            @endcan
            @can('viewAny', App\Entities\Account::class)
                <a class="nav_link {{request()->is('accounts*') ? 'active' : ''}}" href="{{ route('accounts.index') }}" title="{{ __('Accounts') }}">
                  <i class="bx bxs-credit-card"></i>
                  <span class="nav_name">{{ __('Accounts') }}</span>
                </a>
            @endcan

            <!-- ------------------------------------ -->
            @can('viewAny', App\Entities\Backup\DriveDB::class)
                <hr style="margin-left:1rem; color:white;">
                <a class="nav_link {{request()->is('backups*') ? 'active' : ''}}" href="{{ route('backups.index') }}" title="{{ __('Backups') }}">
                  <i class='bx bxs-data'></i>
                  <span class="nav_name">{{ __('Backups') }}</span>
                </a>
            @endcan
            @can('viewAny', App\Entities\Settings::class)
                <a class="nav_link {{request()->is('settings*') ? 'active' : ''}}" href="{{ route('settings.index') }}" title="{{ __('Settings') }}">
                  <i class="bx bxs-cog"></i>
                  <span class="nav_name">{{ __('Settings') }}</span>
                </a>
            @endcan
            <!--
            <hr style="margin-left:1rem; color:white;">
            <a class="nav_link {{request()->is('help*') ? 'active' : ''}}" href="{{ route('faqs') }}" title="{{ __('Help') }}">
              <i class="bx bxs-help-circle"></i>
              <span class="nav_name">{{ __('FAQs') }}</span>
            </a>
            -->
            <hr style="margin-left:1rem; color:white;">
            <a href="{{ route('logout') }}" class="nav_link" title="{{ __('Logout') }}">
                <i class="bx bx-log-out"></i>
              <span class="nav_name">{{ __('Logout') }}</span>
            </a>
          </div>
        </div>
      </nav>
    </div>

    <div class="height-100 py-3">
        @if(Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
            @php Session::forget('success'); @endphp
        @endif
        @if (count($errors) > 0)
            <!--
            @php var_dump($errors) @endphp
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
        <!--<h1 class="h2">@yield('title', 'CSJ')</h1>-->
        <div class="btn-toolbar">
          @yield('btn-toolbar')
        </div>
        @yield('content')
    </div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src='{{ asset("js/bootstrap.bundle.min.js") }}'></script>
    <script src='{{ asset("js/feather.min.js") }}'></script>
    <script src='{{ asset("js/chart.min.js") }}'></script>
    <script src='{{ asset("/js/dashboard.js") }}'></script>
    <script src="{{ asset('js/custom/form-collections.js') }}"></script>
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
        //const linkColor = document.querySelectorAll(".nav_link");

        //function colorLink() {
        //  if (linkColor) {
        //    linkColor.forEach((l) => l.classList.remove("active"));
        //    this.classList.add("active");
        //  }
        //}
        //linkColor.forEach((l) => l.addEventListener("click", colorLink));

        // Your code to run since DOM is loaded and ready
      });
    </script>
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
    <script>
        function authCard() {
            var cards = $('.auth-card');
            cards.eq(0).is(':hidden') ? 
                cards.each(function(i, e) {$(e).show();}) :
                cards.each(function(i, e) {$(e).hide();});
        }
    </script>
    <script>
        function copyToClipboard(el) {
            var text = el.attr('data-clip');
            navigator.clipboard.writeText(text).then(function () {
                //alert("'"+text+"' copied, do a CTRL - V to paste")
                //el.closest('.btn-group').find('.clip').eq(0);
                el.closest('.btn-group').find('.clip:first').show().delay(500).fadeOut();
            }, function () {
                alert("Failure to copy. Check permissions for clipboard")
            });
        }
    </script>
    @yield('scripts')
  </body>
</html>

