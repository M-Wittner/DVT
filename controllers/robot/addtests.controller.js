myApp.controller('addTestsRobotCtrl', ['$scope', '$http', '$location', 'Flash', 'Session', '$cookies', 'AuthService', '$window', 'testParams', '$routeParams', function ($scope, $http, $location, Flash, Session, $cookies, AuthService, $window, testParams, $routeParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.testParams = testParams;

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
	$scope.plan.id = $routeParams.id;
	
	$scope.testCount = [{}];
	$scope.addTest = function(){
		$scope.testCount.push({})
	}
	
	$scope.insertTest = function(test){
		$scope.planParams.push(this.test);
		$scope.lock = true;
	}
	$scope.editToggle = function(){
		$scope.lock = false;
		$scope.planParams.splice(this.test, 1);
	}
	
	$scope.removeTest = function() {
		$scope.testCount.splice($scope.testCount.length-1,1);
	}
	$scope.calc = false;
	$scope.showCalc = function(){
		$scope.calc = !$scope.calc;
	};
	
	$scope.addTestToPlan = function() {
		$http.post('http://wigig-584/robot/addTests', {plan: $scope.plan, tests: $scope.array})
		.then(function(response){
//			if(response.data == 'success'){
//				var message = 'Plan Created Succesfully!';
//				var id = Flash.create('success', message, 3500);
//				$location.path('/plans/'+$scope.plan.id);
//				console.log(response.data)
//			} else {
//				var message = response.data;
//				var id = Flash.create('danger', message, 3500);
//				console.log(response.data);
//			}
			console.log(response.data);
		})
	};
}]);