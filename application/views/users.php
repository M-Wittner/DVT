<!DOCTYPE html>
<html ng-app="myApp">
<head>
	<title>DVT Reporter</title>
	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	
	<!-- Angular -->
	<script type="text/javascript" src="https://code.angularjs.org/1.6.4/angular.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-route.min.js"></script>

	<!-- Bootstrap -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/lib/bootstrap-css/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

	<!-- Costum -->
	<script type="text/javascript" src="<?php echo base_url();?>public/js/controllers/app.js"></script>
	<link rel="stylesheet" href="<?php echo base_url();?>public/lib/css/main.css" type="text/css">
	<!--  Register	-->
	<script type="text/javascript" src="<?php echo base_url();?>public/js/controllers/register.controller.js"></script>
	<!--  Reports	-->
	<script type="text/javascript" src="<?php echo base_url();?>public/js/controllers/reports.controller.js"></script>
</head>
<body>
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
            <li><a href="/reporter/reports">All Reports</a></li>
            <li><a href="/reporter/reports/create">New Report</a></li>
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
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<div ng-app="myApp">
	<div class="panel panel-primary" ng-controller="reportCtrl">
		<!-- Default panel contents -->
		<div class="panel-heading">All Reports</div>
		<div class="panel-body">
		</div>
		<!-- Table -->
		<table class="table">
			<thead>
				<tr>
					<th>Date</th>
					<th>Author</th>
					<th>Report Title</th>
					<th>View</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat="report in reports">
					<th>{{ report.date }}</th>
					<td>{{ report.author }}</td>
					<td>{{ report.title }}</td>
					<td><a href="/reports/{{ report.id }}" class="btn btn-success">View</a></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
</body>
</html>

