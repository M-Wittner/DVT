myApp.controller('regCtrl', ['$scope', '$http', function ($scope, $http) {
	$scope.user = {};
	$scope.register = function() {
//		console.log($scope.user);
		$http.post('http://localhost:3000/register', {user: $scope.user})
		.then(function(response){
//			$scope.message = response.data;
			console.log(response.data);
		});
	}
}]);