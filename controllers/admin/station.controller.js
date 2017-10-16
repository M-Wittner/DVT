myApp.controller('stationCtrl', ['$scope', '$location','$http', '$routeParams', 'Flash', 'AuthService', '$window', function ($scope, $location, $http, $routeParams, Flash, AuthService, $window) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	
	if($scope.isAuthenticated == true) {

		} else {
			var message = 'Please Login first!';
			var id = Flash.create('danger', message, 3500);
			$location.path('/');
		};
	$scope.station = {};
	$scope.addStation = function(){
//		console.log($scope.chip);
		$http.post('http://wigig-584/admin/addstation', $scope.station)
		.then(function(response){
			if(response.data == 'success'){
//				$location.path('/plans/');
				var message = 'New station was added successfully';
				var id = Flash.create('success', message, 5000);
				setTimeout(function(){$window.location.reload();}, 2250);
			}else {
				var message = response.data;
				var id = Flash.create('danger', message, 3500);
				console.log(response.data);
			}
		})
	};	
}]);