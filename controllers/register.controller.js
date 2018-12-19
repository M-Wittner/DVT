myApp.controller('regCtrl', ['$scope', '$http', '$location', 'Flash', 'AuthService', function ($scope, $http, $location, Flash, AuthService) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.location = $location.path();
	
	$scope.user = {};
//	var site = $rootScope.site;
	$scope.register = function() {
		$http.post('http://wigig-584/register', $scope.user)
		.then(function(response){
//			console.log(response.data);
			var message = 'Signed-Up Succesfully!';
			var id = Flash.create('success', message, 3500);
			$location.path('/plans');
		});
	}
}]);