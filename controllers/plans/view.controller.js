myApp.controller('viewPlanCtrl', ['$scope', '$route', '$location','$http', '$routeParams', '$window', 'Flash', 'AuthService', 'testParams', 'LS', function ($scope, $route, $location, $http, $routeParams, $window, Flash, AuthService, testParams, LS) {
	
	$scope.isAuthenticated = AuthService.isAuthenticated();
	
	if($scope.isAuthenticated == true) {	
	$http.post('http://wigig-584/plans/show', $routeParams.id)
	.then(function(response){
		$scope.plan = response.data.plan[0];
		$scope.tests = response.data.tests;
//		console.log(response.data);
	});
	
	$http.post('http://wigig-584/plans/showcomments', $routeParams.id)
	.then(function(response){
		$scope.comments = response.data;
//		console.log(response.data);
	});
		
	} else {
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
	};
	
	$scope.params = testParams.params;
	$scope.lock = true;
	
	$scope.removePlan = function() {
		$http.post('http://wigig-584/plans/removePlan', this.plan.id)
		.then(function(response){
			var message = 'Plan Deleted Succesfully!';
			var id = Flash.create('success', message, 3500);
			$location.path('/plans');
		});
	};
	
	$scope.removeTest = function() {
		$http.post('http://wigig-584/plans/removeTest', this.test.id)
		.then(function(response){
			if(response.data = 'success'){
				$window.scrollTo(0, 0);
				var message = 'Test Deleted Succesfully!';
				var id = Flash.create('success', message, 3500);
				setTimeout(function(){$window.location.reload();}, 2250);
			} else{
				$window.scrollTo(0, 0);
				var message = 'Test Was Not Deleted!';
				var id = Flash.create('danger', message, 3500);
				console.log(response.data);
			}
		});
	};
	$scope.removeComment = function() {
		$http.post('http://wigig-584/plans/removeComment', this.comment.id)
		.then(function(response){
			$window.scrollTo(0, 0);
			var message = 'Comment Deleted Succesfully!';
			var id = Flash.create('success', message, 3500);
			setTimeout(function(){$window.location.reload();}, 2250);
		});
	};
	
	$scope.chipStatus = function(chip, testId, index){
		$http.post('http://wigig-584/plans/chipstatus', {chip: chip, planId: $routeParams.id, testId: testId})
		.then(function(response){
			chip = response.data.chip.chip;
			var message = 'Chip '+chip+' Status Updated!';
			var id = Flash.create('success', message, 3500);
			setTimeout(function(){$window.location.reload();}, 2250);
		});
	}
	
	$scope.xifStatus = function(xif, chip, testId){
//		console.log(xif);
		$http.post('http://wigig-584/plans/xifstatus', {xif: xif, chip: chip, planId: $routeParams.id, testId: testId})
		.then(function(response){
			xif = response.data.xif.xif;
			var message = 'XIF '+xif+' Status Updated!';
			var id = Flash.create('success', message, 3500);
			setTimeout(function(){$window.location.reload();}, 2250);
//			$scope.$apply();
//			$route.reload();
		});
	};
	
	
	$scope.reload= function(index){
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