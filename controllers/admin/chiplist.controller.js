myApp.controller('chiplistCtrl', ['$scope', '$location','$http', '$routeParams', 'Flash', 'AuthService', function ($scope, $location, $http, $routeParams, Flash, AuthService) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	
	if($scope.isAuthenticated == true) {
		$http.get('http://wigig-584/admin/chiplist')
		.then(function(response) {
//			console.log(AuthService.isAuthenticated());
			$scope.chips=response.data;
		});

		} else {
			var message = 'Please Login first!';
			var id = Flash.create('danger', message, 3500);
			$location.path('/');
		};
	
}]);