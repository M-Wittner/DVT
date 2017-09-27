myApp.controller('testlistCtrl', ['$scope', '$location','$http', '$routeParams', 'Flash', 'AuthService', '$window', function ($scope, $location, $http, $routeParams, Flash, AuthService, $window) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	
	if($scope.isAuthenticated == true) {
		$http.get('http://wigig-584/admin/testlist')
		.then(function(response) {
//			console.log(AuthService.isAuthenticated());
			$scope.tests=response.data;
		});

		} else {
			var message = 'Please Login first!';
			var id = Flash.create('danger', message, 3500);
			$location.path('/');
		};
	$scope.removeTest = function(){
		var testName = this.test.test_name;
		$http.post('http://wigig-584/admin/removetest', this.test)
		.then(function(response){
			if(response.data == 'success'){
				$window.scrollTo(0, 0);
				var message = 'Test '+testName+' was deleted';
				var id = Flash.create('success', message, 3500);
				setTimeout(function(){$window.location.reload();}, 2000);
			} else{
				$window.scrollTo(0, 0);
				var message = 'Error! Test was not deleted';
				var id = Flash.create('danger', message, 3500);
			}
		});
//		console.log(this.test.test_name);
	}
}]);