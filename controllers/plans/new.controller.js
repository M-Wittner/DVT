myApp.controller('newPlanCtrl', ['$scope', '$timeout', '$http', '$location', 'Flash', 'Session', '$cookies', 'AuthService', '$window', 'testParams', function ($scope, $timeout, $http, $location, Flash, Session, $cookies, AuthService, $window, testParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.testParams = testParams;
//	$scope.checkLineup = false;
	var site = testParams.site;
//	var $select = $scope.$select
	$scope.testStructs = $scope.testParams.structs;

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
		console.log(this.test);
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
	
	$scope.addPair = function(){
		$scope.chipPairs.push({});
	}
	
	$scope.extras = function(test, struct){
		if(!test.struct){
			test.struct = [];
		}
		if(!test.struct[struct.name]){
			test.struct[struct.name] = {};
		}
		if(!test.struct[struct.name].ext){
			test.struct[struct.name].ext = [];
		}else{
			test.struct[struct.name].ext = null;
		}
	}
	
	
	$scope.selectAll = function(test, struct){
//		console.log(test);
//		console.log(struct);
//		console.log(testParams.params.allParams);
		var result = testParams.params.allParams.filter(item => item.config_idx == struct.config_id);
//		console.log(result);
		if(!test.sweeps){
			test.sweeps = [];
		}
		test.sweeps[struct.name] = result;
	};

	$scope.addPlan = function() {
		console.log($scope.planParams);
		console.log($scope.array);
		$http.post(site+'/plans/create', {plan: $scope.plan, test: $scope.array})
		.then(function(response){
			if(response.data == 'success'){
				var message = 'Plan Created Succesfully!';
				var id = Flash.create('success', message, 3500);
				$location.path('/plans');
				console.log(response.data);
			} else {
				$window.scrollTo(0, 0);
				var message = response.data;
				var id = Flash.create('danger', message, 25000);
				console.log(response.data);
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