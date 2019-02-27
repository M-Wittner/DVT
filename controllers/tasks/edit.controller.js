myApp.controller('editTaskCtrl', ['$rootScope', '$location', '$scope', '$routeParams', '$uibModal', '$http', 'Flash', '$cookies', 'AuthService', 'taskParams', function ($rootScope, $location, $scope, $routeParams, $uibModal, $http, Flash, $cookies, AuthService, taskParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.taskParams = taskParams;
	var site = $rootScope.site;
//	console.log($routeParams.id);
	if($scope.isAuthenticated){
		$http.post(site+'/tasks/view', $routeParams.id)
		.then(function(response){
			$scope.task = response.data;
	//		console.log($scope.task);
		});
	}else{
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
	}
	
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