myApp.controller('adminCtrl', ['$scope', '$location','$http', '$routeParams', 'Flash', 'AuthService', function ($scope, $location, $http, $routeParams, Flash, AuthService) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	
	if($scope.isAuthenticated == true) {
//		console.log('authenticated');

		} else {
			var message = 'Please Login first!';
			var id = Flash.create('danger', message, 3500);
			$location.path('/');
		};
	
}]);