myApp.controller('viewRobotPlanCtrl', ['$scope', '$route', '$location','$http', '$routeParams', '$window', 'Flash', 'AuthService', 'testParams', 'LS', function ($scope, $route, $location, $http, $routeParams, $window, Flash, AuthService, testParams, LS) {
	
	$scope.isAuthenticated = AuthService.isAuthenticated();
	
	if($scope.isAuthenticated == true) {	
	$http.post('http://wigig-584/robot/show', $routeParams.id)
	.then(function(response){
		$scope.plan = response.data;
		console.log(response.data);
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
//		console.log(this.plan.id);
		$http.post('http://wigig-584/robot/removePlan', this.plan.id)
		.then(function(response){
			var message = 'Plan Deleted Succesfully!';
			var id = Flash.create('success', message, 3500);
			$location.path('/robot');
		});
	};
	
	$scope.removeTest = function() {
		$http.post('http://wigig-584/robot/removeTest', this.test.id)
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
	
	$scope.testStatus = function(status, testId){
		$http.post('http://wigig-584/robot/teststatus', {status: status, planId: $routeParams.id, testId: testId})
		.then(function(response){
//			console.log($scope);
			status = response.data.status;
			var message = 'Test Status Updated!';
			var id = Flash.create('success', message, 3500);
			setTimeout(function(){$window.location.reload();}, 2250);
		});
	}
	
	
	$scope.reload= function(index){
		$route.reload();
	};
}]);