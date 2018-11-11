myApp.controller('newPlanCtrl', ['$scope', '$timeout', '$http', '$location', 'Flash', 'Session', '$cookies', 'AuthService', '$window', 'testParams', function ($scope, $timeout, $http, $location, Flash, Session, $cookies, AuthService, $window, testParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.testParams = testParams;
	var site = testParams.site;
//	$scope.testStructs = $scope.testParams.structs;
//	console.log($scope);

	if($scope.isAuthenticated == false){
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
		$window.location.reload();
	};
	
	$scope.user = $cookies.getObject('loggedUser');
	$scope.array = [];
	$scope.plan = {};
	$scope.chipPairs = [{}];
	$scope.plan.userId = $scope.user.id;
	$scope.plan.username = $scope.user.username;
	$scope.testCount = [{}];
	$scope.addTest = function(){
		$scope.testCount.push({})
	}
	
	$scope.removeTest = function() {
		$scope.testCount.splice($scope.testCount.length-1,1);
	}

	$scope.addPlan = function() {
		$http.post(site+'/plans/createnew', {plan: $scope.plan, test: $scope.array})
		.then(function(response){
			Flash.clear();
			console.log(response.data);
			if(typeof response.data == "object"){
				response.data.forEach(function(error){
					var message = "<strong>" + error.source +"</strong>" + ": " + error.msg;
					if(error.occurred == true){
						var id = Flash.create('danger', message, 0);
					}else{
						var id = Flash.create('success', message, 0);
					}
					$window.scrollTo(0, 0);
				})	
			}else{
				var message = response.data;
				var id = Flash.create('success', message, 3500);
//				$location.path('/plans');
			}
		})
	};

}]);