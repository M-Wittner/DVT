myApp.controller('viewPlanCtrl', ['$scope', '$location','$http', '$routeParams', 'Flash', 'AuthService', 'testParams', function ($scope, $location, $http, $routeParams, Flash, AuthService, testParams) {
	
	$scope.isAuthenticated = AuthService.isAuthenticated();
	
	if($scope.isAuthenticated == true) {	
	$http.post('http://wigig-584:3000/plans/show', $routeParams.id)
	.then(function(response){
		$scope.plan = response.data.plan[0];
		$scope.tests = response.data.tests;
		console.log($scope.tests);
	});
	
	$http.post('http://wigig-584:3000/plans/showcomments', $routeParams.id)
	.then(function(response){
		$scope.comments = response.data;
	});
		
	} else {
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
	};
	
	$scope.params = testParams.params;
	console.log($scope.params);
	$scope.sort = 'all';
	$scope.class = "glyphicon glyphicon-ok";
	$scope.test = false;
	$scope.status = function(){
		/*if($scope.class = "glyphicon glyphicon-ok") {
			$scope.class = "glyphicon glyphicon-hourglass";
		} else if ($scope.class = "glyphicon glyphicon-hourglass") {
			$scope.class = "glyphicon glyphicon-remove";
		} else {
			$scope.class = "glyphicon glyphicon-ok";
		}*/
		$scope.test = !$scope.test;
	}
	
	
	$scope.remove = function() {
		$http.post('http://wigig-584:3000/plans/remove', this.plan.id)
		.then(function(response){
			var message = 'Plan Deleted Succesfully!';
			var id = Flash.create('success', message, 3500);
			$location.path('/plans');
		});
	};
}]);