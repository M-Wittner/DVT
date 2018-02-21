myApp.controller('viewTaskCtrl', ['$scope', '$route', '$location','$http', '$routeParams', '$window', 'Flash', 'AuthService', 'testParams', 'LS', '$cookies', function ($scope, $route, $location, $http, $routeParams, $window, Flash, AuthService, testParams, LS, $cookies) {
	
	$scope.isAuthenticated = AuthService.isAuthenticated();
	var site = testParams.site;
	$scope.params = testParams.params;
	if($scope.isAuthenticated == true) {
		
		$http.post(site+'/tasks/view', $routeParams.id)
		.then(function(response){
			$scope.task = response.data;
				
			console.log($scope.task);
		})
		
		$scope.statusUpdate = function(taskId, status){
			$http.post(site+'/tasks/statusUpdate', {id: taskId, status: status})
			.then(function(response){
//			console.log(response.data);
			if(response.data != false){
				$scope.task.status = response.data;
			}
		})
		}
		$scope.priorityUpdate = function(taskId, priority){
			$http.post(site+'/tasks/priorityUpdate', {id: taskId, priority: priority})
			.then(function(response){
//			console.log(response.data);
			if(response.data != false){
				$scope.task.priority = response.data;
			}
		})
		}
		$scope.approveUpdate = function(taskId, approved){
		$http.post(site+'/tasks/approveUpdate', {id: taskId, approved: approved})
			.then(function(response){
			console.log(response.data);
		})
		}
		
	$scope.deleteTask = function(taskId){
		$http.post(site+'/tasks/delete', taskId)
		.then(function(response){
			console.log(response.data);
			if(response.data == 'true'){
				var message = 'Task Deleted Succesfully!';
				var id = Flash.create('success', message, 3500);
				$location.path('/tasks');
			}
		})
		}
	}
}]);