myApp.controller('viewReportCtrl', ['$scope', '$location','$http', function ($scope, $location, $http) {
	$scope.class = "glyphicon glyphicon-ok"
	$scope.status = function(){
		if($scope.class = "glyphicon glyphicon-ok") {
			$scope.class = "glyphicon glyphicon-hourglass";
		} else if ($scope.class = "glyphicon glyphicon-hourglass") {
			$scope.class = "glyphicon glyphicon-remove";
		} else {
			$scope.class = "glyphicon glyphicon-ok";
		}
	}
}]);