<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta name="description" content="Admin, Dashboard, Bootstrap" />
	<link rel="shortcut icon" sizes="196x196" href="{{ asset('assets/images/logo.png') }}">
	<title>DCK Project Report - @yield('title')</title>

  @section('style')
	<link rel="stylesheet" href="{{ asset('libs/bower/font-awesome/css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset('libs/bower/material-design-iconic-font/dist/css/material-design-iconic-font.css') }}">
	<!-- build:css ../assets/css/app.min.css -->
	<link rel="stylesheet" href="{{ asset('libs/bower/animate.css/animate.min.css') }}">
	<link rel="stylesheet" href="{{ asset('libs/bower/fullcalendar/dist/fullcalendar.min.css') }}">
	<link rel="stylesheet" href="{{ asset('libs/bower/perfect-scrollbar/css/perfect-scrollbar.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/core.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
	<link rel="stylesheet" href="{{ asset('libs/bower/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}">
  @show

	<script src="{{ asset('libs/bower/breakpoints.js/dist/breakpoints.min.js') }}"></script>
	<script>
		Breakpoints();
	</script>
</head>
	
<body class="menubar-left menubar-unfold menubar-light theme-primary">
<!--============= start main area -->

<!-- APP NAVBAR ==========-->
<nav id="app-navbar" class="navbar navbar-inverse navbar-fixed-top primary">
  
  <!-- navbar header -->
  <div class="navbar-header">
    <button type="button" id="menubar-toggle-btn" class="navbar-toggle visible-xs-inline-block navbar-toggle-left hamburger hamburger--collapse js-hamburger">
      <span class="sr-only">Toggle navigation</span>
      <span class="hamburger-box"><span class="hamburger-inner"></span></span>
    </button>

    <button type="button" class="navbar-toggle navbar-toggle-right collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
      <span class="sr-only">Toggle navigation</span>
      <span class="zmdi zmdi-hc-lg zmdi-more"></span>
    </button>

    <button type="button" class="navbar-toggle navbar-toggle-right collapsed" data-toggle="collapse" data-target="#navbar-search" aria-expanded="false">
      <span class="sr-only">Toggle navigation</span>
      <span class="zmdi zmdi-hc-lg zmdi-search"></span>
    </button>

    <a href="{{ url('/') }}" class="navbar-brand">
      <span class="brand-icon"><i class="fa fa-star-o"></i></span>
      <span class="brand-name">DCK</span>
    </a>
  </div><!-- .navbar-header -->
  
  <div class="navbar-container container-fluid">
    <div class="collapse navbar-collapse" id="app-navbar-collapse">
      <ul class="nav navbar-toolbar navbar-toolbar-left navbar-left">
        <li class="hidden-float hidden-menubar-top">
          <a href="javascript:void(0)" role="button" id="menubar-fold-btn" class="hamburger hamburger--arrowalt is-active js-hamburger">
            <span class="hamburger-box"><span class="hamburger-inner"></span></span>
          </a>
        </li>
        <li>
          <h5 class="page-title hidden-menubar-top hidden-float">@yield('title')</h5>
        </li>
      </ul>

      <ul class="nav navbar-toolbar navbar-toolbar-right navbar-right">
        <li class="dropdown">
          <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="zmdi zmdi-hc-lg zmdi-settings"></i></a>
          <ul class="dropdown-menu animated flipInY">
            <li><a href="{{ URL('logout') }}"><i class="zmdi m-r-md zmdi-hc-lg zmdi-info"></i>Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div><!-- navbar-container -->
</nav>
<!--========== END app navbar -->

<!-- APP ASIDE ==========-->
<aside id="menubar" class="menubar light">
  <div class="app-user">
    <div class="media">
      <div class="media-left">
        <div class="avatar avatar-md avatar-circle">
        <?php 
          $username =  \Session::get('user') !== null ? \Session::get('user')->vc_username : '';
          $fotoUser = file_exists(public_path().'/images/foto/'.$username.'.jpg') ? $username : 'default';
        ?>
          <a href="javascript:void(0)"><img class="img-responsive" src="{{ asset('images/foto/'.$fotoUser.'.jpg') }}" alt="avatar"/></a>
        </div><!-- .avatar -->
      </div>
      <div class="media-body">
        <div class="foldable">
          <h5><a href="javascript:void(0)" class="username">{{ \Session::get('user') !== null ? \Session::get('user')->vc_emp_name : '' }}</a></h5>
          <ul>
            <li class="dropdown">
              <a href="javascript:void(0)" class="dropdown-toggle usertitle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <small>{{ \Session::get('user') !== null ? \Session::get('user')->vc_dept_name : '' }}</small>
                </a>
            </li>
          </ul>
        </div>
      </div><!-- .media-body -->
    </div><!-- .media -->
  </div><!-- .app-user -->

  <div class="menubar-scroll">
    <div class="menubar-scroll-inner">
      <ul class="app-menu">
        @foreach($navigations as $navigation)
        <li class="has-submenu">
          <a href="javascript:void(0)" class="submenu-toggle">
            <i class="{{ $navigation['icon'] }}"></i>
            <span class="menu-text">{{ $navigation['label'] }}</span>
            <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i>
          </a>
          @if(!empty($navigation['children']))
          <ul class="submenu">
            @foreach($navigation['children'] as $child)
            <li><a href="{{ url($child['route']) }}"><span class="menu-text">{{ $child['label'] }}</span></a></li>
            @endforeach
          </ul>
          @endif
        </li>
        @endforeach
      </ul><!-- .app-menu -->
    </div><!-- .menubar-scroll-inner -->
  </div><!-- .menubar-scroll -->
</aside>
<!--========== END app aside -->

<!-- navbar search -->
<div id="navbar-search" class="navbar-search collapse">
  <div class="navbar-search-inner">
    <form action="#">
      <span class="search-icon"><i class="fa fa-search"></i></span>
      <input class="search-field" type="search" placeholder="search..."/>
    </form>
    <button type="button" class="search-close" data-toggle="collapse" data-target="#navbar-search" aria-expanded="false">
      <i class="fa fa-close"></i>
    </button>
  </div>
  <div class="navbar-search-backdrop" data-toggle="collapse" data-target="#navbar-search" aria-expanded="false"></div>
</div><!-- .navbar-search -->

<!-- APP MAIN ==========-->
<main id="app-main" class="app-main">
  <div class="wrap">
	<section class="app-content">
    @if(Session::has('successMessage'))               
    <div class="alert alert-success alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
      <span>{{ Session::get('successMessage') }}</span>
    </div>
    @endif

    @if(Session::has('errorMessage'))
    <div class="alert alert-danger alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
      <span>{{ Session::get('successMessage') }}</span>
    </div>
    @endif

		@section('content')

    @show
	</section><!-- .app-content -->
</div><!-- .wrap -->
  <!-- APP FOOTER -->
  <div class="wrap p-t-0">
    <footer class="app-footer">
      <div class="clearfix">
        <div class="copyright pull-left">SE DCK &copy; 2017 </div>
      </div>
    </footer>
  </div>
  <!-- /#app-footer -->
</main>
<!--========== END app main -->

	<!-- APP CUSTOMIZER -->
	<div id="app-customizer" class="app-customizer">
		<a href="javascript:void(0)" 
			class="app-customizer-toggle theme-color" 
			data-toggle="class" 
			data-class="open"
			data-active="false"
			data-target="#app-customizer">
			<i class="fa fa-gear"></i>
		</a>
		<div class="customizer-tabs">
			<!-- tabs list -->
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#menubar-customizer" aria-controls="menubar-customizer" role="tab" data-toggle="tab">Menubar</a></li>
				<li role="presentation"><a href="#navbar-customizer" aria-controls="navbar-customizer" role="tab" data-toggle="tab">Navbar</a></li>
			</ul><!-- .nav-tabs -->

			<div class="tab-content">
				<div role="tabpanel" class="tab-pane in active fade" id="menubar-customizer">
					<div class="hidden-menubar-top hidden-float">
						<div class="m-b-0">
							<label for="menubar-fold-switch">Fold Menubar</label>
							<div class="pull-right">
								<input id="menubar-fold-switch" type="checkbox" data-switchery data-size="small" />
							</div>
						</div>
						<hr class="m-h-md">
					</div>
					<div class="radio radio-default m-b-md">
						<input type="radio" id="menubar-light-theme" name="menubar-theme" data-toggle="menubar-theme" data-theme="light">
						<label for="menubar-light-theme">Light</label>
					</div>

					<div class="radio radio-inverse m-b-md">
						<input type="radio" id="menubar-dark-theme" name="menubar-theme" data-toggle="menubar-theme" data-theme="dark">
						<label for="menubar-dark-theme">Dark</label>
					</div>
				</div><!-- .tab-pane -->
				<div role="tabpanel" class="tab-pane fade" id="navbar-customizer">
					<!-- This Section is populated Automatically By javascript -->
				</div><!-- .tab-pane -->
			</div>
		</div><!-- .customizer-taps -->
		<hr class="m-0">
		<div class="customizer-reset">
			<button id="customizer-reset-btn" class="btn btn-block btn-outline btn-primary">Reset</button>
		</div>
	</div><!-- #app-customizer -->
  
  @section('script')
  <!-- build:js ../assets/js/core.min.js -->
  <script src="{{ asset('libs/bower/jquery/dist/jquery.js') }}"></script>
  <script src="{{ asset('libs/bower/jquery-ui/jquery-ui.min.js') }}"></script>
  <script src="{{ asset('libs/bower/jQuery-Storage-API/jquery.storageapi.min.js') }}"></script>
  <script src="{{ asset('libs/bower/bootstrap-sass/assets/javascripts/bootstrap.js') }}"></script>
  <script src="{{ asset('libs/bower/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
  <script src="{{ asset('libs/bower/perfect-scrollbar/js/perfect-scrollbar.jquery.js') }}"></script>
  <script src="{{ asset('libs/bower/PACE/pace.min.js') }}"></script>
  <!-- endbuild -->

  <!-- build:js ../assets/js/app.min.js -->
  <script src="{{ asset('assets/js/library.js') }}"></script>
  <script src="{{ asset('assets/js/plugins.js') }}"></script>
  <script src="{{ asset('assets/js/app.js') }}"></script>
  <!-- endbuild -->
  <script src="{{ asset('libs/bower/moment/moment.js') }}"></script>
  <script src="{{ asset('libs/bower/fullcalendar/dist/fullcalendar.min.js') }}"></script>
  <script src="{{ asset('assets/js/fullcalendar.js') }}"></script>
  <script type="text/javascript">
    setTimeout(function(){
      window.location = '{{ URL('/') }}/logout';
    }, 240 * 60 * 1000);
  </script>
  @show
</body>
</html>