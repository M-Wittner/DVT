var myApp = angular.module('myApp', ['ngRoute']);

myApp.config(['$routeProvider','$locationProvider', function($routeProvider, $locationProvider) {
	
	$routeProvider
		.when('/', {
		templateUrl: 'pages/home.html',
		controller: 'registerCtrl'
	})
		.when('/register', {
		templateUrl: 'pages/register.html',
		controller: 'registerCtrl'
	})
		.when('/reports', {
			templateUrl: 'pages/reports/index.html',
			controller: 'reportCtrl'
	})
		.when('/reports/new', {
			templateUrl: 'pages/reports/new.html',
			controller: 'reportCtrl'
	})
		.when('/reports/:id', {
			templateUrl: 'pages/reports/view.html',
			controller: 'reportCtrl'
	})
		.when('reports/:id/edit', {
			templateUrl: 'pages/reports/edit.html',
			controller: ''
	})
//		.otherwise({redirectTo: '/'});
	
	$locationProvider.hashPrefix('');
//		.html5Mode({
//			enabled: true,
//			requireBase: false
//	});
}]);

