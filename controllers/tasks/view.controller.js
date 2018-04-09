myApp.controller('viewTaskCtrl', ['$scope', '$route', '$location','$http', '$routeParams', '$window', 'Flash', 'AuthService', 'testParams', 'LS', '$cookies', function ($scope, $route, $location, $http, $routeParams, $window, Flash, AuthService, testParams, LS, $cookies) {
	
	$scope.isAuthenticated = AuthService.isAuthenticated();
	var site = testParams.site;
	$scope.params = testParams.params;
	if($scope.isAuthenticated == true) {
		$scope.sender = {
			'userId' : $cookies.getObject('loggedUser').userId,
			'username' : $cookies.getObject('loggedUser').username,
			'email' : $cookies.getObject('loggedUser').email,
		}
		
		$http.post(site+'/tasks/view', $routeParams.id)
		.then(function(response){
			$scope.task = response.data;
//			console.log($scope.task);
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
			if(response.data != false){
				$scope.task.priority = response.data;
			}
		})
	}
		
	$scope.assignedUpdate = function(task, user){
		$http.post(site+'/tasks/assignedUpdate', {task: task, user: user, sender: $scope.sender, site: site})
			.then(function(response){
			if(response.data != false){
				var message = 'Task was assigned to '+response.data+"! Email has been sent";
				var id = Flash.create('success', message, 3500);
				$scope.task.assigned = response.data
				$scope.task.approved = true;
			}else{
				var message = 'Task was not assigned to '+response.data;
				var id = Flash.create('danger', message, 3500);
			}
			console.log(response.data);
		})
	}	
	$scope.imDone = function(task){
		$http.post(site+'/tasks/imDone', {task: task, user: $scope.sender, site: site})
		.then(function(response){
			console.log(response.data);
			if(response.data != false){
				$scope.task.status = response.data;
			}
		})
	}
		
	$scope.activeTask = function(taskId, active){
		$http.post(site+'/tasks/active', {taskId : taskId, active: active})
		.then(function(response){
//			console.log(response.data);
			if(response.data == 'true'){
				var message = 'Task Active Status updated Succesfully!';
				var id = Flash.create('success', message, 3500);
				setTimeout(function(){$window.location.reload();}, 2000);
			}
		})
	}
	$scope.deleteTask = function(taskId){
		$http.post(site+'/tasks/delete', taskId)
		.then(function(response){
//			console.log(response.data);
			if(response.data == 'true'){
				var message = 'Task Deleted Succesfully!';
				var id = Flash.create('success', message, 3500);
				$location.path('/tasks');
			}
		})
	}
	
	$scope.deleteComment = function(taskId, commentId){
		console.log(taskId);
		console.log(commentId);
		$http.post(site+'/tasks/deleteComment', {taskId: taskId, commentId: commentId})
		.then(function(response){
			console.log(response.data);
			if(response.data == 'true'){
				var message = 'Comment Deleted Succesfully!';
				var id = Flash.create('success', message, 3500);
				setTimeout(function(){$window.location.reload();}, 2000);
			}
		})
		}
	}
}]);