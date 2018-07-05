myApp.controller('editPlanCtrl', ['$scope', '$location','$http', '$routeParams', 'AuthService', 'Flash', 'testParams', function ($scope, $location, $http, $routeParams, AuthService, Flash, testParams) {
	
	$scope.isAuthenticated = AuthService.isAuthenticated();
	if($scope.isAuthenticated == true) {
		var site = testParams.site;
	
	$scope.user = $scope.currentUser.username;
		
	$scope.testOld = {};
	$scope.test = {};
	$http.post(site+'/plans/get_test', $routeParams)
	.then(function(response){
		if(response.data.flag == 0){
			$scope.testOld = response.data;
			$scope.testOld.mcs = parseInt(response.data.mcs);
			$scope.testOld.voltage = parseInt(response.data.voltage);
		} else if(response.data.flag == 1){
			$scope.test = response.data;
			if($scope.test.station_id != 5){
				$scope.test.mcs = parseInt(response.data.mcs)
			}
			$scope.test.vlotage = parseInt(response.data.vlotage);
		}else {
			$scope.test = response.data;
		}
		console.log(response.data);
//		console.log($scope.test);
	});
		
	} else {
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
	};
	
	$scope.testParams = testParams;
	$scope.planParams;
	$scope.lock = false;
	
	$scope.addTest1 = function(){
		$scope.lock = true;
	};
	
	$scope.editToggle = function(){
		$scope.lock = !$scope.lock;
	};
	
	$scope.addPair = function(){
		$scope.test.chips.push({});
	}
	$scope.removePair = function(test){
		$scope.test.chips.splice($scope.test.chips.length-1, 1);
	}
	
	$scope.editPlan = function(){
		$http.post(site+'/plans/update', $scope.test)
		.then(function(response){
			if(response.data == 'success'){
				$location.path('/plans/'+$routeParams.planId);
				var message = 'Test was edited successfully';
				var id = Flash.create('success', message, 5000);
			}else {
				var message = response.data;
				var id = Flash.create('danger', message, 3500);
				console.log(response.data);
			}
		});
//		console.log(this.test.station);
	};
}]);