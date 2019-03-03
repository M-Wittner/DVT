myApp.controller('editPlanCtrl', ['$scope', '$rootScope','$location','$http', '$stateParams', 'AuthService', 'Flash', 'testParams', function ($scope, $rootScope, $location, $http, $stateParams, AuthService, Flash, testParams) {
	
	$scope.isAuthenticated = AuthService.isAuthenticated();
	if($scope.isAuthenticated == true) {
		var site = $rootScope.site;
	
	$scope.user = $scope.currentUser.username;
		$scope.testParams = testParams;
		$scope.testStructs = $scope.testParams.structs;
		$scope.testOld = {};
		$scope.test = {};
//		console.log($stateParams);
		$http.get(site+'/plans/GetTest/'+$stateParams.testId)
		.then(function(response){
			$scope.test = response.data;
			$scope.test.priority = [{value: $scope.test.priority}];
			console.log(response.data);
			console.log($scope.test);
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
		$http.post(site+'/plans/update', this.test)
		.then(function(response){
			console.log(response.data);
			if(typeof response.data == "string"){
				$location.path('/plans/');
				var message = response.data;
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