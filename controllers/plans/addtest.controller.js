myApp.controller('addTestCtrl', ['$scope', '$http', '$location', 'Flash', 'Session', '$cookies', 'AuthService', '$window', 'testParams', '$routeParams', function ($scope, $http, $location, Flash, Session, $cookies, AuthService, $window, testParams, $routeParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.testParams = testParams;
	var site = testParams.site;

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
	$scope.plan.id = $routeParams.planId;
	$scope.chipPairs = [{}];
	
	$scope.addPair = function(){
		$scope.chipPairs.push({});
	}
	
	$scope.testCount = [{}];
	$scope.addTest = function(){
		$scope.testCount.push({})
	}
	
	$scope.insertTest = function(data){
		$scope.planParams.push(this.test);
		$scope.lock = true;
		console.log(this);
		console.log(data);
	}
	$scope.editToggle = function(){
		$scope.lock = false;
		$scope.planParams.splice(this.test, 1);
//		console.log($scope);
		console.log(this.test);
	}
	
	$scope.removeTest = function() {
		$scope.testCount.splice($scope.testCount.length-1,1);
	}
	$scope.calc = false;
	$scope.showCalc = function(){
		$scope.calc = !$scope.calc;
	};

	$scope.selectAll = function(test, sweep){
		console.log(test);
		console.log(sweep);
		var result = testParams.params.allParams.filter(item => item.config_id == sweep.config_id);
		console.log(result);
		if(!test.sweeps){
			test.sweeps = [];
		}
		test.sweeps[sweep.name].data = result;
	};

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
//				$location.path('/plans');
			}
		})
//		console.log($scope.array);
	};
	
	$scope.copyTest = function(){
		$http.post(site+'/plans/copyTest', $scope.copyId)
		.then(function(response){
			console.log(response.data);
			$scope.test = response.data;
		})
//		console.log($scope.copyId);
	}
}]);