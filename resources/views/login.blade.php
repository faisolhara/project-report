<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>DCK Project Report - Sign in</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta name="description" content="Admin, Dashboard, Bootstrap" />
	<link rel="shortcut icon" sizes="196x196" href="{{ asset('assets/images/logo.png') }}">
	
	<link rel="stylesheet" href="{{ asset('libs/bower/font-awesome/css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset('libs/bower/material-design-iconic-font/dist/css/material-design-iconic-font.min.css') }}">
	<link rel="stylesheet" href="{{ asset('libs/bower/animate.css/animate.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/core.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/misc-pages.css') }}">
</head>
<body class="simple-page">
	<div id="back-to-home">
		<a href="http://apps.bukitjaya.com/" class="btn btn-outline btn-default"><i class="fa fa-home animated zoomIn"></i></a>
	</div>
	<div class="simple-page-wrap">
		<div class="simple-page-logo animated swing">
			<a href="">
				<span><i class="fa fa-star-o"></i></span>
				<span>DCK Project Report</span>
			</a>
		</div><!-- logo -->
		<div class="simple-page-form animated flipInY" id="login-form">
			<h4 class="form-title m-b-xl text-center">Sign In</h4>
			<form method="post" action="{{ url('post-login') }}">
			    {{ csrf_field() }}
				<div class="form-group">
					<input id="sign-in-email" name="initial" type="text" class="form-control" placeholder="Initial">
				</div>

				<div class="form-group">
					<input id="sign-in-password" name="password" type="password" class="form-control" placeholder="Password">
				</div>

				<input type="submit" class="btn btn-primary" value="SIGN IN">
			</form>
		</div><!-- #login-form -->
	</div><!-- .simple-page-wrap -->

	<script type="text/javascript">
		setTimeout(function(){
			location.reload();
		}, 240 * 60 * 1000);
	</script>
</body>
</html>