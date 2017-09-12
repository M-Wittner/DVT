myApp.controller('editPlanCtrl', ['$scope', '$location','$http', '$routeParams', 'AuthService', 'Flash', 'testParams', function ($scope, $location, $http, $routeParams, AuthService, Flash, testParams) {
	
	$scope.isAuthenticated = AuthService.isAuthenticated();
	if($scope.isAuthenticated == true) {
	
	$scope.test = {};
	$http.post('http://wigig-584/plans/edit', $routeParams)
	.then(function(response){
		$scope.plan = response.data.plan[0];
		$scope.test = response.data.test[0];
		$scope.test.antennas = response.data.antennas;
		$scope.test.channels = response.data.channels;
		$scope.test.temps = response.data.temps;
		$scope.test.chips = response.data.chips;
		$scope.test.mcs = parseInt(response.data.test[0].mcs);
		$scope.test.voltage = parseInt(response.data.test[0].voltage);
		$scope.test.pinFrom = parseInt(response.data.test[0].pin_from);
		$scope.test.pinTo = parseInt(response.data.test[0].pin_to);
		$scope.test.pinStep = parseInt(response.data.test[0].pin_step);
		$scope.test.pinAdd = parseInt(response.data.test[0].pin_additional);
//		console.log($scope.station);
		console.log(response.data.xifs);
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
//		$scope.planParams.push($scope.test);
		$scope.lock = true;
//		console.log($scope.tests);
	};
	
	$scope.editToggle = function(){
		$scope.lock = !$scope.lock;
//		$scope.planParams.splice($scope.planParams.length-1,1);
	};
	
	$scope.editPlan = function(){
		$http.put('http://wigig-584/plans/update', {plan: $scope.plan, test: $scope.test})
		.then(function(response){
			if(response.data == 'success'){
				$location.path('/plans/'+$routeParams.planId);
				var message = 'Test was edited successfully';
				var id = Flash.create('success', message, 5000);
			}else {
				console.log(response.data);
			}
		});
	};
	
}]);