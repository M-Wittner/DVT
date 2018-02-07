myApp.controller('robotCtrl', ['$scope', '$location','$http', 'Flash', '$cookies', '$window', 'AuthService', function ($scope, $location, $http, Flash, $cookies, $window, AuthService) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	
//	$scope.user = $scope.currentUser.username;
//	console.log($scope.currentUser);
	
	if($scope.isAuthenticated == true) {
		$http.get('http://wigig-584/robot')
		.then(function(response) {
			console.log(response.data);
			console.log($scope);
			$scope.plans=response.data;
		});
		$scope.view = function(data){
			$location.path('/robot/'+data);
		};
	} else {
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
	};
}]);