myApp.controller('iterationlistCtrl', ['$scope', '$location','$http', '$routeParams', 'Flash', 'AuthService', '$window', function ($scope, $location, $http, $routeParams, Flash, AuthService, $window) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	
	if($scope.isAuthenticated == true) {
		$http.get('http://wigig-584/params/iterations')
		.then(function(response) {
//			console.log(response.data);
			$scope.tests=response.data;
		});

		} else {
			var message = 'Please Login first!';
			var id = Flash.create('danger', message, 3500);
			$location.path('/');
		};
	$scope.removeStation = function(){
		var stationName = this.station.station;
		$http.post('http://wigig-584/admin/removestation', this.station)
		.then(function(response){
			if(response.data == 'success'){
				$window.scrollTo(0, 0);
				var message = 'Station: '+stationName+' was deleted';
				var id = Flash.create('success', message, 3500);
				setTimeout(function(){$window.location.reload();}, 2000);
			} else{
				$window.scrollTo(0, 0);
				var message = 'Error! Station was not deleted';
				var id = Flash.create('danger', message, 3500);
			}
		});
	}
//	console.log($scope);
}]);