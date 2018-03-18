myApp.controller('editTaskCtrl', ['$scope', '$http', '$location', '$routeParams', 'Flash', 'Session', '$cookies', 'AuthService', '$window', 'testParams', function ($scope, $http, $location, $routeParams, Flash, Session, $cookies, AuthService, $window, testParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.testParams = testParams;
	var site = testParams.site;
//	console.log($routeParams.id);
	$http.post(site+'/tasks/view', $routeParams.id)
	.then(function(response){
		$scope.task = response.data;
//		console.log($scope.task);
	});
	
	$scope.editTask = function(){
		$http.post(site+'/tasks/edit', $scope.task)
		.then(function(response){
			console.log(response.data);
			if(response.data == 'true'){
				var message = 'Task Edited Succesfully!';
				var id = Flash.create('success', message, 3500);
				$location.path('/tasks/');
			} else{
				var message = response.data;
				var id = Flash.create('danger', message, 3500);
			}
		})
	}


}]);