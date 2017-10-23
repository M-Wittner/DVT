myApp.controller('editStationCtrl', ['$scope', '$location','$http', '$routeParams', 'Flash', 'AuthService', '$window', function ($scope, $location, $http, $routeParams, Flash, AuthService, $window) {
$scope.isAuthenticated = AuthService.isAuthenticated();
	
if($scope.isAuthenticated == true) {

	$http.post('http://wigig-584/admin/editstation', $routeParams)
	.then(function(response){
//		console.log(response.data);
		$scope.station = response.data[0];
	});
		$scope.editStation = function(){
//			console.log($scope.station);
			$http.post('http://wigig-584/admin/updatestation', $scope.station)
			.then(function(response){
				if(response.data == 'success'){
	//				$location.path('/plans/');
					var message = 'Station was edited successfully';
					var id = Flash.create('success', message, 5000);
					setTimeout(function(){$location.path('/admin/stationlist');}, 2250);
				}else {
					var message = response.data;
					var id = Flash.create('danger', message, 3500);
					console.log(response.data);
				}
			})
		};

	} else {
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
	};	
}]);