myApp.controller('chiplistCtrl', ['$scope', '$location','$http', '$routeParams', 'Flash', 'AuthService', '$window', function ($scope, $location, $http, $routeParams, Flash, AuthService, $window) {
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
	$scope.removeChip = function(chipId, chipSN){
		$http.post('http://wigig-584/admin/removechip', chipId)
		.then(function(response){
			if(response.data == 'success'){
				$window.scrollTo(0, 0);
				var message = 'Chip '+chipSN+' was deleted';
				var id = Flash.create('success', message, 3500);
			} else{
				$window.scrollTo(0, 0);
				var message = 'Error! Chip was not deleted';
				var id = Flash.create('danger', message, 3500);
			}
		});
	}
}]);