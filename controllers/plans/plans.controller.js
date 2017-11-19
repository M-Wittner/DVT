myApp.controller('plansCtrl', ['$scope', 'NgTableParams', '$location','$http', 'Flash', '$cookies', '$window', 'AuthService', function ($scope, NgTableParams, $location, $http, Flash, $cookies, $window, AuthService) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	
//	$scope.user = $scope.currentUser.username;
//	console.log($scope.currentUser);
	
	if($scope.isAuthenticated == true) {
		$http.get('http://wigig-584/plans')
		.then(function(response) {
			$scope.tableParams = new NgTableParams({count:12}, {
				counts:[],
				total: response.data.length,
				dataset: response.data
			})
			$scope.plans=response.data;
//			console.log(response.data);
		});
		$scope.view = function(data){
			$location.path('/plans/'+data);
		};
	} else {
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
	};
//	console.log();
	$scope.seen = function(plan){
		$http.post('http://wigig-584/plans/planCheck', {plan: plan, user: $scope.currentUser})
		.then(function(response){
//			console.log(response.data);
			if(response.data == 'true'){
				var message = 'Plan Marked As Seen';
				var id = Flash.create('success', message, 3500);
			} else{
				var message = 'Plan Marked As Unseen';
				var id = Flash.create('danger', message, 3500);
			}
//			setTimeout(function(){$window.location.reload();}, 2500);
		});
	}
	
	$scope.tooltip= function(id){
		$http.post('http://wigig-584/plans/planStatus', id)
		.then(function(response){
//			console.log(response.data);
			$scope.tests = response.data;
		});
	}
}]);