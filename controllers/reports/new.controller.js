myApp.controller('newReportCtrl', ['$scope', '$http', '$log', function ($scope, $http, $log) {
	$scope.array = [];
//	$scope.plan = {};
	
	$scope.testCount = [{}];
	$scope.addTest = function(){
		$scope.testCount.push({})
	}
	
	$scope.removeTest = function() {
		$scope.testCount.splice($scope.testCount.length-1,1);
	}
		
	$scope.addPlan = function() {
//		console.log($scope.array[0]);
//		console.log($scope.plan);
//		console.log($scope.chips);
		$http.post('http://localhost:3000/plans/create', {plan: $scope.plan, test: $scope.array})
		.then(function(response){
			if(response.data){
				console.log(response.data);
			} else {
				console.log(response);
			}
		})
	};
}]);