myApp.controller('newTaskCtrl', ['$rootScope', '$location', '$scope', '$routeParams', '$uibModal', '$http', 'Flash', '$cookies', 'AuthService', 'taskParams', '$state', function ($rootScope, $location, $scope, $routeParams, $uibModal, $http, Flash, $cookies, AuthService, taskParams, $state) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.taskParams = taskParams;
	var site = $rootScope.site;
	if(!$scope.isAuthenticated()){
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
	}
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
		}).then(function(){
				$state.go('tasks');
		})
	}

}]);