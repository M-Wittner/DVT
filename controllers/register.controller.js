myApp.controller('regCtrl', ['$scope', '$http', '$location', 'Flash', function ($scope, $http, $location, Flash) {
	$scope.user = {};
	$scope.register = function() {
//		console.log($scope.user);
		$http.post('http://localhost:3000/register', {user: $scope.user})
		.then(function(response){
			console.log(response.data);
			var message = 'Signed-Up Succesfully!';
			var id = Flash.create('success', message, 3500);
			$location.path('/plans');
		});
	}
}]);