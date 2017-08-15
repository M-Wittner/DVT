myApp.controller('editPlanCtrl', ['$scope', '$location','$http', '$routeParams', 'AuthService', 'Flash', 'testParams', function ($scope, $location, $http, $routeParams, AuthService, Flash, testParams) {
	
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.testParams = testParams;
	$scope.test = {};
	$scope.planParams;
	$scope.lock = false;
	
	$scope.addTest1 = function(){
		$scope.planParams.push($scope.test);
		$scope.lock = true;
	};
	
	$scope.editToggle = function(){
		$scope.lock = !$scope.lock;
		$scope.planParams.splice($scope.planParams.length-1,1);
	};
	
	if($scope.isAuthenticated == true) {
		
	$http.post('http://wigig-584:3000/plans/show', $routeParams.id)
	.then(function(response){
		$scope.plan = response.data.plan[0];
		$scope.tests = response.data.tests;
	});
		
	} else {
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
	};
}]);