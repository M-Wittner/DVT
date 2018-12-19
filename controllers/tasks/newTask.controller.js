myApp.controller('newTaskCtrl', ['$rootScope', '$scope', '$routeParams', '$uibModal', '$http', 'Flash', '$cookies', 'AuthService', 'taskParams', function ($rootScope, $scope, $routeParams, $uibModal, $http, Flash, $cookies, AuthService, taskParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.taskParams = taskParams;
	var site = $rootScope.site;
	$scope.user = {
		'userId' : $cookies.getObject('loggedUser').id,
		'username' : $cookies.getObject('loggedUser').username,
		'email' : $cookies.getObject('loggedUser').email,
	}
	
	$scope.task = {};
	
	$scope.createTask = function(){
		$http.post(site+'/tasks/create', {task: $scope.task, user: $scope.user})
		.then(function(response){
			if(response.data == 'true'){
				var message = 'Task Created Succesfully!';
				var id = Flash.create('success', message, 3500);
				console.log(response.data);
			} else {
				var message = response.data;
				var id = Flash.create('danger', message, 3500);
				console.log(response.data);
			}
		});
	}

}]);