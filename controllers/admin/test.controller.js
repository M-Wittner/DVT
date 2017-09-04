myApp.controller('testCtrl', ['$scope', '$location','$http', '$routeParams', 'Flash', 'AuthService', 'testParams', function ($scope, $location, $http, $routeParams, Flash, AuthService, testParams) {
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
			console.log(response.data);
		});
	};
}]);