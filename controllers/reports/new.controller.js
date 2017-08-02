myApp.controller('newReportCtrl', ['$scope', '$http', '$location', function ($scope, $http, $location) {
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
				$location.path('/reports');
			} else {
				console.log(response);
			}
		})
//		console.log($scope.array);
	};
}]);