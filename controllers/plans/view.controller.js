myApp.controller('viewPlanCtrl', ['$scope', '$location','$http', function ($scope, $location, $http) {
	$scope.planId = {};
	$http.get('http://localhost:3000/plans/show', )
	.then(function(response){
		$scope.planId.id = response.data;
		$http.post('http://localhost:3000/plans/show', {id: $scope.planId})
		.then(function(response){
			console.log(response.data);
		});
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