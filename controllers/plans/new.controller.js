myApp.controller('newPlanCtrl', ['$scope', '$http', '$location', 'Flash', 'Session', '$cookies', 'AuthService', '$window', 'testParams', function ($scope, $http, $location, Flash, Session, $cookies, AuthService, $window, testParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.testParams = testParams;
//	$scope.checkLineup = false;
	var site = testParams.site;
//	console.log($scope.testParams);

	if($scope.isAuthenticated == false){
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
		$window.location.reload();
	};
	
	$scope.array = [];
	$scope.plan = {};
	$scope.chipPairs = [{}];
	$scope.plan.userId = $cookies.getObject('loggedUser').userId;
	$scope.plan.username = $cookies.getObject('loggedUser').username;
	
	$scope.testCount = [{}];
	$scope.addTest = function(){
		$scope.testCount.push({})
	}
	
	$scope.insertTest = function(){
		$scope.planParams.push(this.test);
		$scope.lock = true;
//		console.log($scope.test);
		console.log(this);
	}
	$scope.editToggle = function(){
		$scope.lock = false;
		$scope.planParams.splice(this.test, 1);
//		console.log($scope);
//		console.log(this.test);
	}
	
	$scope.removeTest = function() {
		$scope.testCount.splice($scope.testCount.length-1,1);
	}
	$scope.calc = false;
	$scope.showCalc = function(){
		$scope.calc = !$scope.calc;
	};
	
	$scope.addPair = function(){
		$scope.chipPairs.push({});
	}

	$scope.addPlan = function() {
		$http.post(site+'/plans/create', {plan: $scope.plan, test: $scope.array})
		.then(function(response){
			console.log(response.data);
			if(response.data == 'success'){
				var message = 'Plan Created Succesfully!';
				var id = Flash.create('success', message, 3500);
				$location.path('/plans');
			}else if(typeof(response.data) == 'object'){
				var data = response.data
				var result = [];
				for(var key in data){
//					console.log(typeof(data[key]));
					if(typeof(data[key]) == 'object'){
						data[key].forEach(function(msg){
							var message = "<strong>"+key+"</strong>" + ": " + msg;
							var id = Flash.create('danger', message, 0);
						})
					}else{
							var message = "<strong>"+key+"</strong>" + ": " + data[key];
							var id = Flash.create('danger', message, 0);
					}
				}
				$window.scrollTo(0, 0);
			}
//			if(response.data == 'success'){
//				var message = 'Plan Created Succesfully!';
//				var id = Flash.create('success', message, 3500);
//				$location.path('/plans');
//				console.log(response.data);
//			} else {
//				$window.scrollTo(0, 0);
//				var message = response.data;
//				var id = Flash.create('danger', message, 25000);
//				console.log(response.data);
//			}
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