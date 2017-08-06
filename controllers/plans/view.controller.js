myApp.controller('viewPlanCtrl', ['$scope', '$location','$http', '$routeParams', function ($scope, $location, $http, $routeParams) {
	$http.post('http://localhost:3000/plans/show', $routeParams.id)
	.then(function(response){
		console.log(response.data);
		$scope.plan = response.data.plan[0];
		$scope.tests = response.data.tests;
//		$scope.tests.channels = response.tests.channels;
//		console.log($scope.plan);
		console.log(response.data.tests[0].channels);
//		console.log($scope.channels[1]);
	});
	$scope.class = "glyphicon glyphicon-ok";
	$scope.test = false;
	$scope.status = function(){
		/*if($scope.class = "glyphicon glyphicon-ok") {
			$scope.class = "glyphicon glyphicon-hourglass";
		} else if ($scope.class = "glyphicon glyphicon-hourglass") {
			$scope.class = "glyphicon glyphicon-remove";
		} else {
			$scope.class = "glyphicon glyphicon-ok";
		}*/
		$scope.test = !$scope.test;
	}
}]);