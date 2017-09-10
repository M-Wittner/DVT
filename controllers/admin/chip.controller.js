myApp.controller('chipCtrl', ['$scope', '$location','$http', '$routeParams', 'Flash', 'AuthService', function ($scope, $location, $http, $routeParams, Flash, AuthService) {
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
			console.log(response.data)
		})
	};
	
}]);