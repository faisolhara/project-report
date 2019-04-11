
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>DCK Project Report</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta name="description" content="Admin, Dashboard, Bootstrap" />
	<link rel="shortcut icon" sizes="196x196" href="../assets/images/logo.png">
	
	<link rel="stylesheet" href="{{ asset('libs/bower/font-awesome/css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset('libs/bower/material-design-iconic-font/dist/css/material-design-iconic-font.min.css') }}">
	<link rel="stylesheet" href="{{ asset('libs/bower/animate.css/animate.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/core.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/misc-pages.css') }}">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700,800,900,300">
</head>
<body class="simple-page">
	<div id="back-to-home">
		<a href="{{ url('/') }}" class="btn btn-outline btn-default"><i class="fa fa-home animated zoomIn"></i></a>
	</div>
	<div class="simple-page-wrap">
		<div class="simple-page-logo animated swing">
			<a href="index.html">
				<span><i class="fa fa-star"></i></span>
				<span>DCK Project Report</span>
			</a>
		</div><!-- logo -->
		<h1 id="_404_title" class="animated shake">403</h1>
<h5 id="_404_msg" class="animated slideInUp">Oops, an error occur. You don't have permission to acess this page!</h5>
<div id="_404_form" class="animated slideInUp text-center">
	<div class="animated slideInUp">
		<a class="btn btn-default btn-sm" href="{{ \URL::previous() }}"><i class="fa fa-angle-left"></i> Back to Last Page</a>
	</div>
</div>


	</div><!-- .simple-page-wrap -->
</body>
</html>