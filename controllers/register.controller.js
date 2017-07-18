myApp.controller('regCtrl', ['$scope', '$http', function ($scope, $http) {
	$scope.user = {};
	$scope.register = function() {
		$http.post('http://localhost:3000/register', {user: $scope.user})
		.then(function(data){
			$scope.message = data.data;
			console.log(data.data);
		});
	}
}]);