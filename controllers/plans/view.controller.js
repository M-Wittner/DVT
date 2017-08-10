myApp.controller('viewPlanCtrl', ['$scope', '$location','$http', '$routeParams', 'Flash', function ($scope, $location, $http, $routeParams, Flash) {
	$http.post('http://localhost:3000/plans/show', $routeParams.id)
	.then(function(response){
//		console.log(response.data);
		$scope.plan = response.data.plan[0];
		$scope.tests = response.data.tests;
	});
	$scope.edit = function(plan){
		$location.path('/plans/'+plan+'/edit');

		console.log(plan)
	};
	
	$scope.lock = true;
	
	$scope.remove = function(plan){
	$http.post('http://wigig-584:3000/plans/remove', plan)
	.then(function(response){
		$location.url('/plans/');
		var message = 'Plan Deleted Succesfully!';
		var id = Flash.create('success', message, 3500);
		$location.url('/plans/');
	});
	};
//	$scope.class = "glyphicon-ok";
//	$scope.test = false;
	$scope.status = function(){
//		if($scope.class = "glyphicon-ok") {
//			$scope.class = "glyphicon-hourglass";
//		} else if ($scope.class = "glyphicon-hourglass") {
//			$scope.class = "glyphicon-remove";
//		} else {
//			$scope.class = "glyphicon-ok";
//		}
		console.log("click");
		$scope.test = !$scope.test;
	}
}]);