myApp.controller('tasksCtrl', ['$scope', 'NgTableParams', '$location','$http', 'Flash', '$cookies', '$window', 'AuthService', 'testParams', function ($scope, NgTableParams, $location, $http, Flash, $cookies, $window, AuthService, testParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	var site = testParams.site;
	
	$http.get(site+'/tasks')
	.then(function(response){
		$scope.tableParams = new NgTableParams({count:15}, {
			counts: [],
			total: response.data.length,
			dataset: response.data
		});
		console.log(response.data);
	})
	
}]);