myApp.controller('homeCtrl', ['$scope', '$http', function ($scope, $http) {
	$scope.user = {};
	$scope.login = function() {
		$http({
			method: 'POST',
			url: 'http://localhost:3000/login',
			data: $scope.user,
//			dataType: 'JSON',
			headers : {'Content-Type': 'application/x-www-form-urlencoded'}
		})
		.then(function(success, data){
			console.log(data);
			console.log('dsadsa');
		})
		.then(function(error, data){
			console.log(error);
			console.log('aaaaaaaaaaaad');
		})
	}
}]);