myApp.controller('logCtrl', ['$scope','$http', 'Flash', 'AuthService', 'NgTableParams', 'testParams', '$stateParams', function ($scope, $http, Flash, AuthService, NgTableParams, testParams, $stateParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.isAuthenticated = true;
	var site = testParams.site;
	$scope.testParams = testParams;
	$scope.workStations = testParams.params.workStations;
	$scope.operators = testParams.params.operatorList;
	console.log($scope.operators);
	var vm = this;
  
  function today() {
    vm.dt = new Date();
  }
  
  today(); 
  
  vm.opened = false;

  vm.openDatePopup = function() {
		console.log('click');
    vm.opened = true;
  };

  // Disable weekend selection
  vm.disabled = function(date, mode) {
    return mode === 'day' && (date.getDay() === 0 || date.getDay() === 6);
  };
  
  vm.minDate = angular.copy(vm.dt);
  
  var fiveWeekdaysFromNow = angular.copy(vm.dt);
  fiveWeekdaysFromNow.setDate(fiveWeekdaysFromNow.getDate() + 7);
  vm.maxDate = fiveWeekdaysFromNow;

  vm.dateOptions = {
    formatYear: 'yy',
    startingDay: 0
  };

  vm.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
  vm.format = vm.formats[0];
	
	if($scope.isAuthenticated == true) {
		$http.get(site+'/admin/operations')
		.then(function(response) {
			$scope.tableParams = new NgTableParams({count:30}, {
				counts:[],
				total: response.data.length,
				dataset: response.data
			});
			$scope.data = response.data;
			console.log(response.data);
		});

		} else {
			var message = 'Please Login first!';
			var id = Flash.create('danger', message, 3500);
			$location.path('/');
		};
}]);