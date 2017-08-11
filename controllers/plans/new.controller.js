myApp.controller('newPlanCtrl', ['$scope', '$http', '$location', 'Flash', 'Session', '$cookies', 'AuthService', '$window', function ($scope, $http, $location, Flash, Session, $cookies, AuthService, $window) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	
	if($scope.isAuthenticated == false){
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
		$window.location.reload();
	};
	
	$scope.array = [];
	$scope.plan = {};
	$scope.plan.userId = $cookies.getObject('loggedUser').userId;
	$scope.plan.username = $cookies.getObject('loggedUser').username;
	
	$scope.testCount = [{}];
	$scope.addTest = function(){
		$scope.testCount.push({})
	}
	
	$scope.removeTest = function() {
		$scope.testCount.splice($scope.testCount.length-1,1);
	}
		
	$scope.addPlan = function() {
		$http.post('http://wigig-584:3000/plans/create', {plan: $scope.plan, test: $scope.array})
		.then(function(response){
			if(response.data.id){
				var message = 'Plan Created Succesfully!';
				var id = Flash.create('success', message, 3500);
				$location.path('/plans');
				console.log(response.data)
			} else {
				var message = 'Error! Plan was not created!';
				var id = Flash.create('danger', message, 3500);
				$location.path('/plans/new');
				console.log(response.data);
			}
		})
//		console.log($scope.array);
	};
}]);