myApp.controller('viewPlanCtrl', ['$scope', '$route', '$location','$http', '$routeParams', 'Flash', 'AuthService', 'testParams', 'LS', function ($scope, $route, $location, $http, $routeParams, Flash, AuthService, testParams, LS) {
	
	$scope.isAuthenticated = AuthService.isAuthenticated();
	
	if($scope.isAuthenticated == true) {	
	$http.post('http://wigig-584/plans/show', $routeParams.id)
	.then(function(response){
		$scope.plan = response.data.plan[0];
		$scope.tests = response.data.tests;
		console.log(response.data);
	});
	
	$http.post('http://wigig-584/plans/showcomments', $routeParams.id)
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
		$http.post('http://wigig-584/plans/remove', this.plan.id)
		.then(function(response){
			var message = 'Plan Deleted Succesfully!';
			var id = Flash.create('success', message, 3500);
			$location.path('/plans');
		});
	};
	
	$scope.chipStatus = function(chip, testId, index){
		$http.post('http://wigig-584/plans/chipstatus', {chip: chip, planId: $routeParams.id, testId: testId})
		.then(function(response){
//			console.log(response.data.chip.running);
//			console.log(response.data.chip.completed);
//			console.log(response.data.chip.error);
		});
	}
	
	$scope.reload= function(){
		$route.reload();
	};
//	$scope.XifStatus = function(xif, testId, index){
//		$http.post('http://wigig-584/plans/xifstatus', {xif: xif, planId: $routeParams.id, testId: testId})
//		.then(function(response){
////			console.log(response.data.chip.running);
////			console.log(response.data.chip.completed);
////			console.log(response.data.chip.error);
//		});
//	}
}]);