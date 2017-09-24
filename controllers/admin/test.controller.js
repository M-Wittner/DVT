myApp.controller('testCtrl', ['$scope', '$location','$http', '$routeParams', 'Flash', 'AuthService', 'testParams', function ($scope, $location, $http, $routeParams, Flash, AuthService, testParams, $window) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.testParams = testParams;
	
	if($scope.isAuthenticated == true) {

		} else {
			var message = 'Please Login first!';
			var id = Flash.create('danger', message, 3500);
			$location.path('/');
		};
	$scope.test = {}
	$scope.addTest = function(){
		$http.post('http://wigig-584/admin/addtest', $scope.test)
		.then(function(response){
			if(response.data == 'true'){
				var message = 'Test was created';
				var id = Flash.create('success', message, 3500);
			} else {
				var message = 'Error! Test was not created';
				var id = Flash.create('danger', message, 3500);
			}
		});
	};
}]);