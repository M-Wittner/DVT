myApp.controller('tasksCtrl', ['$rootScope', '$scope', 'NgTableParams', '$uibModal', '$location', '$http', 'Flash', '$cookies', '$window', 'AuthService', 'taskParams', '$state', function ($rootScope, $scope, NgTableParams, $uibModal, $location, $http, Flash, $cookies, $window, AuthService, taskParams, $state) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	var site = $rootScope.site;
	var $ctrl = this;
	$scope.taskParams = taskParams;
	$scope.reviewedTasks = false;
	$scope.completedTasks = false;
	$scope.isFilterEnabled = true;	
	if($scope.isAuthenticated == true) {
		$http.get(site + '/tasks')
			.then(function (response) {
			$scope.tasks = response.data;
			$scope.stations = taskParams.stations;
			$scope.statuses = taskParams.statuses;
			$scope.priorities = taskParams.priorities;
			$scope.approved = taskParams.approved;
			$scope.assignees = taskParams.assignees;
			$scope.reviewed = taskParams.reviewed;
			console.log($scope.tasks);
			})
			.then(function(){
				$scope.colsList = [
					{field: 'date', title: 'Date', filter: {date: 'text'}, show: false},
					{field: 'status', title: 'Status', filter: {status_id: 'select'}, filterData: $scope.statuses, show: true},
					{field: 'priority', title: 'Priority', filter: {priority_id: 'select'}, filterData: $scope.priorities, show: true},
					{field: 'station', title: 'Station', filter: {station_id: 'select'}, filterData: $scope.stations, show: true},
					{field: 'title', title: 'Title',filter: {title: 'text'} ,show: true},
					{field: 'creator', title: 'Creator', filter: {creator: 'text'}, show: true},
					{field: 'assigned', title: 'Assigned To', filter: {assigned_id: 'select'}, filterData: $scope.assignees, show: true},
					{field: 'approved', title: 'Approved', filter: {approved: 'select'}, filterData: $scope.approved, show: true},
					{field: 'reviewed', title: 'Reviewed', filter: {reviewed: 'select'}, filterData: $scope.reviewed, show: true},
					{field: 'control', title: 'Control', show: true},
				];
				$scope.cols = _.indexBy($scope.colsList, "field");
		}).then(function(){
				setTimeout(function(){
					$scope.tableTasks = new NgTableParams(
						{count: 15},
						{
							counts: [],
							total: $scope.tasks.length,
							dataset: $scope.tasks,
						}
					);
				}, 100);
		})
	} else {
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
	};

	$scope.activeTask = function (task) {
		var taskId = task.id;
		$http.get(site + '/tasks/active/' + taskId)
			.then(function (response) {
				console.log(response.data);
				console.log(this);
//				if (response.data == 'true') {
					var message = 'Task Deleted Succesfully!';
					var id = Flash.create('success', message, 3500);
					task.active = response.data;
//				}
			}).then(function(){
			console.log(task);
		})
	}
	$scope.deleteTask = function (taskId) {
		$http.get(site + '/tasks/delete/'+taskId)
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