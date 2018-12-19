myApp.controller('tasksCtrl', ['$rootScope', '$scope', 'NgTableParams', '$uibModal', '$location', '$http', 'Flash', '$cookies', '$window', 'AuthService', 'taskParams', function ($rootScope, $scope, NgTableParams, $uibModal, $location, $http, Flash, $cookies, $window, AuthService, taskParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	var site = $rootScope.site;
	var $ctrl = this;
	$scope.taskParams = taskParams;
	console.log($scope.taskParams);
	$scope.inactiveTasks = false;
	$scope.reviewedTasks = false;
	$scope.completedTasks = false;
	$scope.isFilterDisabled = true;
	
	if($scope.isAuthenticated == true) {
		$http.get(site + '/tasks')
			.then(function (response) {
				$scope.tableParams = new NgTableParams({
//					filter: {
//					 status: 'Pending',
//					 status: 'Declined',
//					 status: 'In Progress',
//					},
					count: 15
				}, {
					counts: [],
					total: response.data.length,
					dataset: response.data
				});
				console.log(response.data);
			})
	} else {
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
	};

	$scope.activeTask = function (taskId, active) {
		$http.post(site + '/tasks/active', {
				taskId: taskId,
				active: active
			})
			.then(function (response) {
				//			console.log(response.data);
				if (response.data == 'true') {
					var message = 'Task Deleted Succesfully!';
					var id = Flash.create('success', message, 3500);
					setTimeout(function () {
						$window.location.reload();
					}, 2250);
				}
			})
	}
	$scope.deleteTask = function (taskId) {
		$http.post(site + '/tasks/delete', taskId)
			.then(function (response) {
				//			console.log(response.data);
				if (response.data == 'true') {
					var message = 'Task Deleted Succesfully!';
					var id = Flash.create('success', message, 3500);
					setTimeout(function () {
						$window.location.reload();
					}, 2000);
				}
			})
	}
	
	$scope.viewTask = function(id){
//		$window.location.href()
		$location.path('/tasks/'+id);
	}
	
	$scope.modal = function (taskId, size, parentSelector) {
    var parentElem = parentSelector ? 
      angular.element($document[0].querySelector('.modal-demo ' + parentSelector)) : undefined;
    var modalInstance = $uibModal.open({
      animation: $ctrl.animationsEnabled,
      ariaLabelledBy: 'modal-title',
      ariaDescribedBy: 'modal-body',
      templateUrl: 'pages/partials/modals/deleteTask.html',
      controller: 'tasksCtrl',
      controllerAs: '$ctrl',
      size: size,
      appendTo: parentElem,
			taskId: taskId,
      resolve: {
				taskId: function(){
					return taskId;
				},
        items: function () {
          return taskId;
        },
      }
    });
	}
	
	$scope.cancel = function(){
		console.log($scope.modal);
	}
}]);