myApp.controller('chipCtrl', ['$scope', '$location','$http', '$routeParams', 'Flash', 'AuthService', '$window', '$cookies', function ($scope, $location, $http, $routeParams, Flash, AuthService, $window, $cookies) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	
	if($scope.isAuthenticated == true) {
		 $scope.currentUser = $cookies.getObject('loggedUser');
		console.log($scope.currentUser);

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
				var message = 'New chip was added successfully, Page will NOT refresh for convenience';
				var id = Flash.create('success', message, 5000);
//				setTimeout(function(){$window.location.reload();}, 2250);
			}else {
				var message = response.data;
				var id = Flash.create('danger', message, 3500);
				console.log(response.data);
			}
		})
	};	
}]);