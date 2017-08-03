var myApp = angular.module('myApp', ['ngRoute', 'ui.bootstrap', 'btorfs.multiselect']);

myApp.config(['$routeProvider', '$locationProvider', '$httpProvider', function ($routeProvider, $locationProvider, $httpProvider) {
	
	//$httpProvider.defaults.headers.post['Content-Type'] = 'application/json';
	//$httpProvider.defaults.headers.common['Access-Control-Allow-Origin'] = '*';
	
	$routeProvider
		.when('/', {
		templateUrl: 'pages/home.html',
		controller: 'homeCtrl'
	})
		.when('/register', {
		templateUrl: 'pages/register.html',
		controller: 'regCtrl'
	})
		.when('/plans', {
			templateUrl: 'pages/plans/index.html',
			controller: 'plansCtrl'
	})
		.when('/plans/new', {
			templateUrl: 'pages/plans/new.html',
			controller: 'newPlanCtrl'
	})
		.when('/plans/:id', {
			templateUrl: 'pages/plans/view.html',
			controller: 'viewPlanCtrl'
	})
		.when('plans/:id/edit', {
			templateUrl: 'pages/plans/edit.html',
			controller: ''
	})
		.otherwise({redirectTo: '/'});
	
	$locationProvider.hashPrefix('');

}]);

myApp.directive('testForm', function(){
	return {
		templateUrl: 'pages/plans/newTest.html',
		controller: 'testCtrl',
		scope: {
			planParams: '=',
			params: '=',
			index: '&',
			locked: '='
		},
		link: function(scope, element, attrs){
			
		}
	}
});
