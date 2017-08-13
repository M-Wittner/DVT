myApp.controller('editPlanCtrl', ['$scope', '$location','$http', '$routeParams', function ($scope, $location, $http, $routeParams) {
	
	$http.post('http://wigig-584:3000/plans/show', $routeParams.id)
	.then(function(response){
		console.log(response.data);
		$scope.plan = response.data.plan[0];
		$scope.tests = response.data.tests;
		console.log($scope.tests);
	});
	
	
}]);