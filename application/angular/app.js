var myApp = angular.module('myApp', ['ui.router']);

myApp.config(function($stateProvider) {
  $stateProvider
	  .state('reports', {
	  		url: '/',
	  		templateUrl: 'pages/reports/index.html',
	  		controller: 'reportCtrl'
  });
});
//myApp.controller('reportCtrl', ['$scope', '$location','$http', function ($scope, $location, $http) {
//	$scope.report = {};
////	$http({
////		method: 'GET',
////		url: 'reports',
////		data: {report: 'report'},
////		hearders: {'Content-Type': 'application/json'}
////	}).then(function success(data){
////		$scope.reports=data;
////	}).then(function error(data, status){
////		console.log('error: '+data);
////	});
////	$http.get('http://localhost:3000/reporter/reports/all')
////	.then(function(result) {
////		console.log(result);
////		$scope.reports=result;
////	});
//	
//}]);
//
//	myApp.controller('registerCtrl', ['$scope','$http','$location', function($scope, $http, $location) {
//		$scope.try = "test!!!";
//		console.log("log");	
//}]);

//var welcome = angular.module('welcome');
//welcome.controller('regController',['$scope','$http',
//function ($scope,$http)
//{
//     $scope.user = {};
//    $scope.submitForm=function() {
//    $scope.registrations = {}; 
//       $http({
//        method:'post',
//        dataType : 'json',
//        url:'http://localhost:3000/reporter/welcome/add',
//        data : $scope.user, //forms user object
//        headers : {'Content-Type': 'application/json'} 
//    }).success(function (data)
//    {
//          $scope.registrations=data;
//          
//    });
//   
//}          
//}]);