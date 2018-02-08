myApp.controller('operationsCtrl', ['$scope', 'NgTableParams', '$location','$http', '$routeParams', 'Flash', 'AuthService', '$window', function ($scope, NgTableParams, $location, $http, $routeParams, Flash, AuthService, $window) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.isAuthenticated = true;
	
	if($scope.isAuthenticated == true) {
		$http.get('http://localhost/admin/operations')
		.then(function(response) {
			$scope.tableParams = new NgTableParams({count:30}, {
				counts:[],
				total: response.data.length,
				dataset: response.data
			});
			console.log(response.data);
		});

		} else {
			var message = 'Please Login first!';
			var id = Flash.create('danger', message, 3500);
			$location.path('/');
		};
}]);