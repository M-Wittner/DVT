var app = angular.module('myApp', ['ngRoute']);

app.controller('registerCtrl', ['$scope', '$http', function ($scope, $http) {
	$http({
        method:'post',
		url:'http://localhost:3000/reporter/register',
        //data : $scope.user, //forms user object
        headers : {'Content-Type': 'application/x-www-form-urlencoded'} 
    })

}]);
