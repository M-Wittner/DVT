myApp.controller('regCtrl', ['$scope', '$http', function ($scope, $http) {
	$scope.user = {};
	$scope.register = function() {
//		$http({
//			method: 'POST',
//			url: 'http://localhost:3000/register',
//			data: $scope.user,
////			dataType: 'JSON',
//			headers : {'Content-Type': 'application/x-www-form-urlencoded'}
//		})
		$http.post('http://localhost:3000/register', {user: $scope.user})
		.then(function(data){
			console.log($scope.user);
			$scope.message = data;
//			console.log(data.data);
		});
	}
}]);