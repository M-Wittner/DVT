myApp.controller('homeCtrl', ['$scope', '$http', function ($scope, $http) {
	$scope.user = {};
	$scope.register = function() {
		$http({
			method: 'POST',
			url: 'http://localhost:3000/register',
			data: $scope.user,
			dataType: 'JSON',
			headers : {'Content-Type': 'application/x-www-form-urlencoded'}
		})
		.then(function(success, data){
			console.log(data);
		})
		.then(function(error, data){
			console.log(error);
		})
	}
}]);