<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema Ventas Laravel Vue Js- JT-Code">
    <meta name="author" content="jt-code.com">
    <meta name="keyword" content="Sistema ventas Laravel Vue Js, Sistema compras Laravel Vue Js, Sistema de Compra y venta con Vue.js">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="userId" content="{{Auth::check()? Auth::user()->id : '' }}">
    <title>Sistema Ventas - JT-Code</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js" rel="stylesheet">
    <link href="css/template.css" rel="stylesheet">
  </head>
  <body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
    <div id="app">
      <header class="app-header navbar">
        <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#"></a>
        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
          <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="nav navbar-nav d-md-down-none">
          <li class="nav-item px-3">
            <a class="nav-link" href="#">Dashboard</a>
          </li>
          <li class="nav-item px-3">
            <a class="nav-link" href="#">Configuraciones</a>
          </li>
        </ul>
        <ul class="nav navbar-nav ml-auto">
            <notification-component :notifications="notifications"></notification-component>
            <li class="nav-item dropdown">
              <a class="nav-link " data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <img class="img-avatar" src="img/avatars/6.jpg" alt="admin@bootstrapmaster.com">
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header text-center">
                  <strong>{{ Auth::user()->username }}</strong>
                </div>
                <a class="dropdown-item" href="#">
                  <i class="fa fa-user"></i> Perfil</a>
                <a class="dropdown-item" href="{{ route('logout') }}" onClick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <i class="fa fa-lock"></i> Cerrar Session</a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                  </form>
              </div>
            </li>
          </ul>
      </header>
    
      <div class="app-body">
        @if(Auth::check())
          @if(Auth::user()->rol_id == 1 )
            @include('layout.partials.sidebar-admin')
          @elseif(Auth::user()->rol_id == 2 )
            @include('layout.partials.sidebar-seller')
          @elseif(Auth::user()->rol_id == 3 )
            @include('layout.partials.sidebar-grocer')
          @else

          @endif
        @endif

        <!-- Main Content -->
        @yield('content')
        <!-- End Main Content -->

      </div>
    </div
      {{-- <footer class="app-footer">
          <span><a href="#">JT-Code</a> &copy; 2018</span>
          <span class="ml-auto">Desarrollado por <a href="#">Jorge Tigrero</a></span>
      </footer> --}}

    <!-- CoreUI and necessary plugins-->
    {{-- <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/pace.min.js"></script>
    <script src="js/coreui.min.js"></script> --}}
    <!-- Plugins and scripts required by this view-->
    {{-- <script src="js/Chart.min.js"></script> --}}
    <script src="js/app.js"></script>
    <script src="js/template.js"></script>
  </body>
</html>