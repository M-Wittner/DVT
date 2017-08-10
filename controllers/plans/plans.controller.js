myApp.controller('plansCtrl', ['$scope', '$location','$http', '$log', 'Flash', function ($scope, $location, $http, $log, Flash) {
	$http.get('http://localhost:3000/plans')
	.then(function(response) {
//		console.log(result.data);
		$scope.plans=response.data;
	});
	$scope.view = function(data){
		$location.path('/plans/'+data);
	};
//	console.log($scope);
//	$log.info($location.path());
//	$log.info('test');
}]);