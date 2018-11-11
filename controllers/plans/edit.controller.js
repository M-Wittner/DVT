myApp.controller('editPlanCtrl', ['$scope', '$location','$http', '$routeParams', 'AuthService', 'Flash', 'testParams', function ($scope, $location, $http, $routeParams, AuthService, Flash, testParams) {
	
	$scope.isAuthenticated = AuthService.isAuthenticated();
	if($scope.isAuthenticated == true) {
		var site = testParams.site;
	
	$scope.user = $scope.currentUser.username;
//	$scope.plan.userId = $cookies.getObject('loggedUser').id;
////	$scope.plan.username = $cookies.getObject('loggedUser').username;
		$scope.testParams = testParams;
		$scope.testStructs = $scope.testParams.structs;
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
	
	$scope.selectAll = function(test, sweep, name){
		console.log(test);
		console.log(sweep);
		var result = testParams.params.allParams.filter(item => item.config_id == sweep.config_id);
		console.log(result);
		if(!test.sweeps){
			test.sweeps = [];
		}
		test.sweeps[name].data = result;
	};
	
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
//		console.log(this.test);
	};
}]);