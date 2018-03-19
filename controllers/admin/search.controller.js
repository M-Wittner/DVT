myApp.controller('searchCtrl', ['$scope', '$location','$http', 'Flash', '$cookies', '$window','testParams', 'AuthService', function ($scope, $location, $http, Flash, $cookies, $window, testParams, AuthService) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	var site = testParams.site;
	if($scope.isAuthenticated == true) {
		$http.get(site+'/admin/search')
		.then(function(response) {
//			console.log(AuthService.isAuthenticated());
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
		$http.post(site+'/plans/planCheck', {plan: plan, user: $scope.currentUser})
		.then(function(response){
			console.log(response.data);
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
	
	
//	console.log($scope);
//	$log.info($location.path());
//	$log.info('test');
}]);