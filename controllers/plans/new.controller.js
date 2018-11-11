myApp.controller('newPlanCtrl', ['$scope', '$timeout', '$http', '$location', 'Flash', 'Session', '$cookies', 'AuthService', '$window', 'testParams', function ($scope, $timeout, $http, $location, Flash, Session, $cookies, AuthService, $window, testParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.testParams = testParams;
//	$scope.checkLineup = false;
	var site = testParams.site;
//	var $select = $scope.$select
	$scope.testStructs = $scope.testParams.structs;
//	console.log($scope.testStructs);

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
	
	$scope.insertTest = function(){
		this.test.scopeIndex = this.$id;
		$scope.planParams.push(this.test);
		$scope.lock = true;
		console.log(this.test);
	}
	$scope.editToggle = function(){
		$scope.lock = false;
		var index = $scope.planParams.findIndex(test => test.scopeIndex == this.$id);
		$scope.planParams.splice(index, 1);
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
	
	$scope.extras = function(test, sweep){
		console.log(sweep);
		console.log(test);
		var sweepName = sweep.name;
		if(!test.sweeps){
			test.sweeps = [];
		}
		if(!test.sweeps[sweepName]){
			test.sweeps[sweepName] = {};
		}
		if(!test.sweeps[sweepName].data){
			test.sweeps[sweepName].data = [];
		}			
		if(!test.sweeps[sweepName].data.ext){
			test.sweeps[sweepName].data.ext = [];
		}else{
			test.sweeps[sweepName].data.ext = null;
		}
		console.log(test);
	}
	
	$scope.log = function(structs){
		this.testStructs = $scope.testStructs;
		console.log($scope.testStructs);
		console.log(structs);
		console.log($scope);
	}
	
	
	$scope.loadData = function(sweep){
		var reader = new FileReader();
		var text = reader.readAsText(sweep.fileData);
		var result;
		var array = [];
		var formatedArray = [];
		sweep.data.name = sweep.fileData.name;
		reader.addEventListener("loadend", function() {
			// reader.result contains the contents of blob as a typed array
			result = reader.result;
			array = result.split("\n");
			array.splice(array.length-1, 1);
//			console.log(array);
			array.forEach(function(number){
				var split = number.split(",");
				var shifted = split[0] << 8 | (split[1] & 255);
				var dataObj = {
					'config_id': 19,
					'value': shifted
				}
				sweep.data.push(dataObj);
			})
		});
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