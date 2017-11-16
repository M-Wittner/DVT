myApp.controller('viewPlanCtrl', ['$scope', '$route', '$location','$http', '$routeParams', '$window', 'Flash', 'AuthService', 'testParams', 'LS', function ($scope, $route, $location, $http, $routeParams, $window, Flash, AuthService, testParams, LS) {
	
	$scope.isAuthenticated = AuthService.isAuthenticated();
	
	if($scope.isAuthenticated == true) {	
	$http.post('http://wigig-584/plans/show', $routeParams.id)
	.then(function(response){
//		console.log(response.data);
		$scope.plan = response.data.plan[0];
		$scope.tests = response.data.tests;
		
	});
	
//	$http.post('http://wigig-584/plans/showcomments', $routeParams.id)
//	.then(function(response){
//		$scope.comments = response.data;
////		console.log(response.data);
//	});
		
	} else {
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
	};
	
	$scope.toggleTest = function(index){
//		console.log(index);
		$scope.toggleCollapse = !$scope.toggleCollapse;
		setTimeout($scope.toggleFade = $scope.toggleFade, 1500);
	}
	
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
	
	$scope.panelLink = function(){
		console.log($location.path());
	}
	
	$scope.xifStatus = function(xif){
//		console.log(xif);
		$http.post('http://wigig-584/plans/xifstatus', xif)
		.then(function(response){
			this.xif = response.data[0];
			var message = 'XIF '+this.xif.xif+' Status Updated!';
			var id = Flash.create('success', message, 3500);
			setTimeout(function(){$window.location.reload();}, 2250);
//			console.log(this.xif);
//			$scope.$apply();
//			$route.reload();
		});
	};
	
//	console.log($scope);
	
	$scope.reload= function(index){
		$route.reload();
	};
}]);