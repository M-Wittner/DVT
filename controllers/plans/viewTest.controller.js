myApp.controller('viewTestCtrl', ['$scope','$rootScope', '$route', '$location','$http', '$stateParams', '$window', 'Flash', 'AuthService', 'testParams', '$cookies', 'NgTableParams', function($scope, $rootScope, $route, $location, $http, $stateParams, $window, Flash, AuthService, testParam, $cookies, NgTableParams){
	
	$scope.isAuthenticated = AuthService.isAuthenticated();
		var site = $rootScope.site;
		var scope = $scope;
	if($scope.isAuthenticated == true){	
	
	$http.get(site+'/plans/show_test/' + $stateParams.testId)
	.then(function(response){
		var data = response.data;
		$scope.plan = {};
		$scope.plan.tests = [];
		$scope.plan.id = data.plan_id;
		$scope.plan.tests.push(data);
		$scope.plan.tests[0].isOpen = true;
		console.log(response.data);
	});
		
	$scope.returnName = function(sweepName){
		return sweepName;
	}
	$scope.sweepsTable = function(struct){
//		console.log(struct);
		$scope.cols = [];
		$scope.TableParams = new NgTableParams({
			counts:[],
			total: struct.length,
			dataset: struct
		})
	}

	$scope.user = {};
	$scope.user.id = $cookies.getObject('loggedUser').id;
	$scope.user.username = $cookies.getObject('loggedUser').username;
		
	} else {
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
	}
}]);