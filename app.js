var myApp = angular.module('myApp', ['ngRoute']);

myApp.config(['$routeProvider','$locationProvider', function($routeProvider, $locationProvider) {
	
	$routeProvider
		.when('/reports', {
			templateUrl: 'pages/reports/index.html',
			controller: 'reportCtrl'
	})
//		.otherwise({redirectTo: '/'});
	
	$locationProvider.hashPrefix('');
//		.html5Mode({
//			enabled: true,
//			requireBase: false
//	});
}]);

myApp.controller('reportCtrl', ['$scope', '$location','$http', '$log', function ($scope, $location, $http, $log) {
	$http.get('http://localhost:3000/reports')
	.then(function(result) {
//		console.log(result.data);
		$scope.reports=result.data;
	});
	$log.info($location.path());
//	$log.info('test');
}]);