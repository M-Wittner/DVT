myApp.controller('viewPlanCtrl', ['$scope', '$location','$http', '$routeParams', 'Flash', 'AuthService', 'testParams', 'LS', function ($scope, $location, $http, $routeParams, Flash, AuthService, testParams, LS) {
	
	$scope.isAuthenticated = AuthService.isAuthenticated();
	
	if($scope.isAuthenticated == true) {	
	$http.post('http://wigig-584:3000/plans/show', $routeParams.id)
	.then(function(response){
		$scope.plan = response.data.plan[0];
		$scope.tests = response.data.tests;
		console.log(response.data);
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
	
	$scope.remove = function() {
		$http.post('http://wigig-584:3000/plans/remove', this.plan.id)
		.then(function(response){
			var message = 'Plan Deleted Succesfully!';
			var id = Flash.create('success', message, 3500);
			$location.path('/plans');
		});
	};
	$scope.status = {};
	$scope.status.class = LS.getData();
	$scope.chipStatus = function(chip, testId, index){
//		console.log(LS.getData());
		if($scope.status.class == null ){
			$scope.status.class = 'glyphicon-hourglass';
		} else if($scope.status.class == 'glyphicon-hourglass'){
			$scope.status.class = 'glyphicon-ok';
		} else if($scope.status.class == 'glyphicon-ok'){
			$scope.status.class = 'glyphicon-remove';
		} else{
			$scope.status.class = null;
		}
//		$http.post('http://wigig-584:3000/plans/chipstatus', {status: $scope.status, chip: chip, planId: $routeParams.id, testId: testId})
//		.then(function(response){
//			LS.setData($scope.status);
////			console.log();
//			console.log(response.data);
//		});
	}
}]);