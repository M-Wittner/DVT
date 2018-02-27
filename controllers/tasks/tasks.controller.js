myApp.controller('tasksCtrl', ['$scope', 'NgTableParams', '$location','$http', 'Flash', '$cookies', '$window', 'AuthService', 'testParams', function ($scope, NgTableParams, $location, $http, Flash, $cookies, $window, AuthService, testParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	var site = testParams.site;
	$scope.testParams = testParams.params;
	$scope.activeTasks = false;
//	console.log($scope.testParams);
	
	$http.get(site+'/tasks')
	.then(function(response){
		$scope.tableParams = new NgTableParams({count:15}, {
			counts: [],
			filter: {priority: "high"},
			total: response.data.length,
			dataset: response.data
		});
		console.log(response.data);
	})
	
	$scope.activeTask = function(taskId, active){
		$http.post(site+'/tasks/active', {taskId : taskId, active: active})
		.then(function(response){
//			console.log(response.data);
			if(response.data == 'true'){
				var message = 'Task Deleted Succesfully!';
				var id = Flash.create('success', message, 3500);
				setTimeout(function(){$window.location.reload();}, 2250);
			}
		})
	}
	
}]);