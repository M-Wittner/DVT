myApp.controller('editPlanCtrl', ['$scope', '$location','$http', '$routeParams', 'AuthService', 'Flash', 'testParams', function ($scope, $location, $http, $routeParams, AuthService, Flash, testParams) {
	
	$scope.isAuthenticated = AuthService.isAuthenticated();
	if($scope.isAuthenticated == true) {
	
		
	$http.post('http://wigig-584/plans/edit', $routeParams)
	.then(function(response){
		$scope.plan = response.data.plan[0];
		$scope.test = response.data.test[0];
//		$scope.chips = response.data.test[0].chips;
//		$scope.channels = response.data.test[0].channels;
//		$scope.temps = response.data.test[0].temps;
//		$scope.antennas = response.data.test[0].antennas;
//		console.log(response.data);
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
		$http.put('http://wigig-584/plans/update', {plan: $scope.plan, test: $scope.test, chips: $scope.chips, antennas: $scope.antennas, temps: $scope.temps, channels: $scope.channels})
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