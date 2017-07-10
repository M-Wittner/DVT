myApp.controller('reportCtrl', ['$scope', '$location','$http', '$log', function ($scope, $location, $http, $log) {
	$http.get('http://localhost:3000/reports')
	.then(function(result) {
//		console.log(result.data);
		$scope.reports=result.data;
	});
//	$log.info($location.path());
//	$log.info('test');
}]);