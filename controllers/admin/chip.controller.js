myApp.controller('chipCtrl', ['$scope', '$location','$http', '$routeParams', 'Flash', 'AuthService', '$window', function ($scope, $location, $http, $routeParams, Flash, AuthService, $window) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	
	if($scope.isAuthenticated == true) {

		} else {
			var message = 'Please Login first!';
			var id = Flash.create('danger', message, 3500);
			$location.path('/');
		};
	$scope.chip = {};
	$scope.addChip = function(){
//		console.log($scope.chip);
		$http.post('http://wigig-584/admin/addchip', $scope.chip)
		.then(function(response){
			if(response.data == 'success'){
//				$location.path('/plans/');
				var message = 'New chip was added successfully';
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