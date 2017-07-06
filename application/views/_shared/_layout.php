<!doctype html>
<html lang="en" ng-app="app">

<head>
	<meta charset="UTF-8">
	<title>DVT Repporter</title>

	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<!-- Angular -->
	<script type="text/javascript" src="https://code.angularjs.org/1.6.4/angular.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/angular-material/1.1.4/angular-material.min.css" />
	<script src="http://ajax.googleapis.com/ajax/libs/angular_material/1.1.4/angular-material.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-aria.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-messages.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-route.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-resource.js" type="text/javascript"></script>


	<!-- Bootstrap -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/lib/bootstrap-css/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

	<!-- Costum -->
	<link rel="stylesheet" href="<?php echo base_url();?>public/lib/css/main.css" type="text/css">
	<script type="text/javascript" src="<?php echo base_url();?>public/js/controllers/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>public/js/controllers/reports.controller.js"></script>
</head>

<body ng-controller="appCtrl">
	<div class="container">
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
					<a class="navbar-brand" href="/">Home</a>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<!--        <% if (currentUser) { %>-->
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reports <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="/reports">All Reports</a></li>
								<li><a href="/reports/new">New Report</a></li>
							</ul>
						</li>
						<li class="pull-right"><a href="#">Logged in as M</a></li>
						<li><a href="/logout">Log Out</a></li>
						<!--        <% } else { %>-->
						<li><a href="/register">Sign up</a></li>
						<!--        <% } %>-->
					</ul>
					<form class="navbar-form navbar-right">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Search">
						</div>
						<button type="submit" class="btn btn-default">Submit</button>
					</form>
				</div>
				<!-- /.navbar-collapse -->
			</div>
			<!-- /.container-fluid -->
		</nav>

		<div class="container">
			<?php echo $data ?>
		</div>
</body>

</html>