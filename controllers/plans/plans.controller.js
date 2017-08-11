myApp.controller('plansCtrl', ['$scope', '$location','$http', 'Flash', '$cookies', 'AuthService', function ($scope, $location, $http, Flash, $cookies, AuthService) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	
	if($scope.isAuthenticated == true) {
		$http.get('http://wigig-584:3000/plans')
		.then(function(response) {
//			console.log(AuthService.isAuthenticated());
			$scope.plans=response.data;
		});
		$scope.view = function(data){
			$location.path('/plans/'+data);
		};
	} else {
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
	};
	
	
//	console.log($scope);
//	$log.info($location.path());
//	$log.info('test');
}]);