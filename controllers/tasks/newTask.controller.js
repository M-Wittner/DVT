myApp.controller('newTaskCtrl', ['$scope', '$http', '$location', 'Flash', 'Session', '$cookies', 'AuthService', '$window', 'testParams', function ($scope, $http, $location, Flash, Session, $cookies, AuthService, $window, testParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.testParams = testParams;
	var site = testParams.site;
	$scope.user = {
		'userId' : $cookies.getObject('loggedUser').userId,
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
				$location.path('/tasks');
				console.log(response.data);
			} else {
				var message = response.data;
				var id = Flash.create('danger', message, 3500);
				console.log(response.data);
			}
		});
	}

}]);