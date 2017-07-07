app.controller('registerCtrl', ['$scope', '$http', function ($scope, $http) {
	$http.post('http://localhost:3000/register');
}]);