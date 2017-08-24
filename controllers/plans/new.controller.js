myApp.controller('newPlanCtrl', ['$scope', '$http', '$location', 'Flash', 'Session', '$cookies', 'AuthService', '$window', 'testParams', function ($scope, $http, $location, Flash, Session, $cookies, AuthService, $window, testParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	
	$scope.testParams = testParams;
//	console.log($scope.testParams);
	console.log($scope.testParams.params);
	$scope.testParams.nameSettings = { groupByTextProvider: function(groupValue) { if (groupValue === 'M') { return 'M'; } else { return 'R'; } }, groupBy: 'station', };
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
//	console.log(String.indexOf('gdsgfdgfd'));
	$scope.removeTest = function() {
		$scope.testCount.splice($scope.testCount.length-1,1);
	}
		
	$scope.addPlan = function() {
		$http.post('http://wigig-584:3000/plans/create', {plan: $scope.plan, test: $scope.array})
		.then(function(response){
			if(response.data == 'success'){
				var message = 'Plan Created Succesfully!';
				var id = Flash.create('success', message, 3500);
				$location.path('/plans');
				console.log(response.data)
			} else {
				var message = response.data;
				var id = Flash.create('danger', message, 3500);
				console.log(response.data);
			}
		})
//		console.log($scope.array);
	};
}]);