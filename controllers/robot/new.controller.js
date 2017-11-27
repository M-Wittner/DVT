myApp.controller('newRobotPlanCtrl', ['$scope', '$http', '$location', 'Flash', 'Session', '$cookies', 'AuthService', '$window', 'testParams', function ($scope, $http, $location, Flash, Session, $cookies, AuthService, $window, testParams) {
//	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.isAuthenticated = true;
	$scope.testParams = testParams;
//	console.log($scope.testParams);

	if($scope.isAuthenticated == false){
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
		$window.location.reload();
	};
	
	$scope.arr = [];
	$scope.plan = {};
	$scope.plan.userId = $cookies.getObject('loggedUser').userId;
	$scope.plan.username = $cookies.getObject('loggedUser').username;
	
	$scope.testsCount = [{}];
	$scope.addTest = function(){
		$scope.testsCount.push({})
	}
	
	$scope.insertTest = function(){
		$scope.test.push(this.test);
		$scope.lock = true;
		console.log(this.test);
		console.log($scope);
	}
	$scope.editToggle = function(){
		$scope.lock = false;
		$scope.plan.tests(this.test, 1);
	}
	
	$scope.removeTest = function() {
		$scope.testsCount.splice($scope.testsCount.length-1,1);
	}
	$scope.calc = false;
	$scope.showCalc = function(){
		$scope.calc = !$scope.calc;
	};

	$scope.addPlan = function() {
//		$http.post('http://wigig-584/robot/create', {plan: $scope.plan, test: $scope.array})
//		.then(function(response){
//			console.log(response.data);
//		});
		console.log($scope);
	};
}]);