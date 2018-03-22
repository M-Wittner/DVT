myApp.controller('regCtrl', ['$scope', '$http', '$location', 'Flash', 'AuthService', function ($scope, $http, $location, Flash, AuthService) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.location = $location.path();
	
	$scope.user = {};
	$scope.register = function() {
//		console.log($scope.user);
		$http.post('http://wigig-584/register', $scope.user)
		.then(function(response){
			console.log(response.data);
			if(response.data == 'OK'){
				var message = 'Signed-Up Succesfully!';
				var id = Flash.create('success', message, 3500);
				$location.path('/plans');
			}else {
				var message = response.data;
				var id = Flash.create('danger', message, 3500);
			}
		});
	}
}]);