myApp.controller('newPlanCtrl', ['$scope', '$http', '$location', 'Flash', function ($scope, $http, $location, Flash) {
	$scope.array = [];
	$scope.plan = {};
	
	$scope.testCount = [{}];
	$scope.addTest = function(){
		$scope.testCount.push({})
	}
	
	$scope.removeTest = function() {
		$scope.testCount.splice($scope.testCount.length-1,1);
	}
		
	$scope.addPlan = function() {
		$http.post('http://localhost:3000/plans/create', {plan: $scope.plan, test: $scope.array})
		.then(function(response){
			if(response.data){
				var message = 'Plan Created Succesfully!';
				var id = Flash.create('success', message, 3500);
				$location.path('/plans');
				console.log(response.data)
			} else {
				console.log(response);
			}
		})
//		console.log($scope.array);
	};
}]);