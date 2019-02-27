myApp.controller('addTestCtrl', ['$scope', '$rootScope', '$http', '$location', 'Flash', 'Session', '$cookies', 'AuthService', '$window', 'testParams', '$routeParams', function ($scope, $rootScope, $http, $location, Flash, Session, $cookies, AuthService, $window, testParams, $routeParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.testParams = testParams;
	var site = $rootScope.site;

	if($scope.isAuthenticated == false){
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
		$window.location.reload();
	};
	
	$scope.array = [];
	$scope.plan = {};
	$scope.plan.userId = $cookies.getObject('loggedUser').id;
	$scope.plan.username = $cookies.getObject('loggedUser').username;
	$scope.plan.id = $routeParams.planId;
	
	$scope.testCount = [{}];
	$scope.addTest = function(){
		$scope.testCount.push({})
	}
	
	$scope.removeTest = function() {
		$scope.testCount.splice($scope.testCount.length-1,1);
	}

//	$scope.selectAll = function(test, sweep){
//		console.log(test);
//		console.log(sweep);
//		var result = testParams.params.allParams.filter(item => item.config_id == sweep.config_id);
//		console.log(result);
//		if(!test.sweeps){
//			test.sweeps = [];
//		}
//		test.sweeps[sweep.name].data = result;
//	};

	$scope.addPlan = function() {
		$http.post(site+'/plans/createnew', {plan: $scope.plan, test: $scope.array})
		.then(function(response){
			Flash.clear()
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
				$location.path('/today');
			}
		})
	};
	
	$scope.copyTest = function(){
		$http.post(site+'/plans/copyTest', $scope.copyId)
		.then(function(response){
			console.log(response.data);
			$scope.test = response.data;
		})
	}
}]);