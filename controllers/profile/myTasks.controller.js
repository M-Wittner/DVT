myApp.controller('myTasksCtrl', ['$scope', '$rootScope', '$state', 'NgTableParams', '$uibModal', '$location', '$http', 'Flash', '$cookies', '$window', 'AuthService', 'testParams', '$routeParams', function ($scope, $rootScope, $state, NgTableParams, $uibModal, $location, $http, Flash, $cookies, $window, AuthService, testParams, $routeParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	var site = $rootScope.site;
	var $ctrl = this;
	$scope.testParams = testParams.params;
	$scope.activeTasks = true;
	$scope.completedTasks = true;
	$scope.isFilterDisabled = true;
	console.log($rootScope);
	
	if(($scope.isAuthenticated == true && $state.params.username == $rootScope.currentUser.username)){
		$http.get(site + '/profile/mytasks/' + $rootScope.currentUser.id)
		.then(function (response) {
			$scope.tableParams = new NgTableParams({
				count: 15
			}, {
				counts: [],
				total: response.data.length,
				dataset: response.data
			});
			console.log(response.data);
		})
	} else{
		$location.path("/plans");
//		console.log($state.params.username);
//		console.log($scope.currentUser.username);
		var message = 'You can only view you own tasks';
		var id = Flash.create('danger', message, 3500);
	}

	$scope.viewTask = function(id){
		$location.path('/tasks/'+id);
	}

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