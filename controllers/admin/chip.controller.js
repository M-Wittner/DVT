myApp.controller('chipCtrl', ['$scope', '$rootScope', '$location', '$http', '$routeParams', 'Flash', 'AuthService', '$window', '$cookies', function ($scope, $rootScope, $location, $http, $routeParams, Flash, AuthService, $window, $cookies) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	var site = $rootScope.site;
	if ($scope.isAuthenticated == true) {
		$scope.currentUser = $cookies.getObject('loggedUser');
		console.log($scope.currentUser);
		$http.get(site+ '/admin/chipParams')
		.then(function(response){
			console.log(response.data);
			$scope.params = response.data;
		})
	} else {
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
	};
	$scope.chip = {};
	$scope.addChip = function () {
				console.log($scope.chip);
//		$http.post(site + '/admin/addchip', $scope.chip)
//			.then(function (response) {
//				if (response.data == 'success') {
//					//				$location.path('/plans/');
//					var message = 'New chip was added successfully, Page will NOT refresh for convenience';
//					var id = Flash.create('success', message, 5000);
//					//				setTimeout(function(){$window.location.reload();}, 2250);
//				} else {
//					var message = response.data;
//					var id = Flash.create('danger', message, 3500);
//					console.log(response.data);
//				}
//			})
	};
	
	$scope.extras = function(data, paramName){
		if(!data[paramName]){
			data[paramName] = {};
		}
		if(!data[paramName].ext){
				data[paramName].ext = [];
		}else {
			delete data[paramName].ext;
		}
	}
}]);