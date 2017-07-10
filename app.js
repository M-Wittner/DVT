var myApp = angular.module('myApp', ['ngRoute', 'ui.bootstrap', 'btorfs.multiselect']);

myApp.config(['$routeProvider','$locationProvider', function($routeProvider, $locationProvider) {
	
	$routeProvider
		.when('/', {
		templateUrl: 'pages/home.html',
		controller: 'homeCtrl'
	})
		.when('/register', {
		templateUrl: 'pages/register.html',
		controller: 'regCtrl'
	})
		.when('/reports', {
			templateUrl: 'pages/reports/index.html',
			controller: 'reportCtrl'
	})
		.when('/reports/new', {
			templateUrl: 'pages/reports/new.html',
			controller: 'newReportCtrl'
	})
		.when('/reports/:id', {
			templateUrl: 'pages/reports/view.html',
			controller: 'viewReportCtrl'
	})
		.when('reports/:id/edit', {
			templateUrl: 'pages/reports/edit.html',
			controller: ''
	})
		.otherwise({redirectTo: '/'});
	
	$locationProvider.hashPrefix('');

}]);

