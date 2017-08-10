myApp.controller('homeCtrl', ['$scope', '$http', function ($scope, $http) {
	$scope.user = {};
	$scope.login = function() {
		$http.post('http://localhost:3000/login', {user: $scope.user})
		.then(function(data){
			if(data.data == 'form Invalid') {
				$scope.message = 'invalid';
			} else {
				$scope.message = 'sussss';
			}
		});
	}
}]);

myApp.factory('myApp',['$scope', '$http', '$cookies', '$rootScope', '$timeout', 'UserService'], function($scope, $http, $cookies, $rootScope, $timeout, UserService) {
	var service = {};
	
	service.Login = Login;
	service.SetCredentials = SetCradentials;
	service.ClearCredentials = ClearCredentials;
	
	return service;
	
	$scope.user = {};
	$scope.Login = function() {
		$http.post('http://localhost:3000/login', {user: $scope.user})
		.then(function(data){
			if(data == null) {
				$scope.message = 'Data in Null'
			} else {
				$scope.message = data.data;				
			}
		});
	}
});