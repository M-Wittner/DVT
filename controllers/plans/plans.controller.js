myApp.controller('plansCtrl', ['$scope', '$location','$http', '$log', function ($scope, $location, $http, $log) {
//	$scope.plan = {};
	$http.get('http://localhost:3000/plans')
	.then(function(response) {
//		console.log(result.data);
		$scope.plans=response.data;
	});
//	$scope.plan = {};
	$scope.planId = {};
	$scope.view = function(data){
		$scope.planId.id = data
		$http.post('http://localhost:3000/plans/show', {id: $scope.planId})
		.then(function(response){
			if(response.data){
				$location.path('/plans/'+data);
			} else {
				console.log(response);
			}
		});
	};
//	console.log($scope);
//	$log.info($location.path());
//	$log.info('test');
}]);