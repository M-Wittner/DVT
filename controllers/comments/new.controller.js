myApp.controller('newCommentCtrl', ['$scope', '$location','$http', '$routeParams', 'Flash', 'AuthService', function ($scope, $location, $http, $routeParams, Flash, AuthService) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	
	if($scope.isAuthenticated == true) {
		
		$http.post('http://wigig-584/plans/edit', $routeParams)
		.then(function(response){
			$scope.tests = response.data.test;
			$scope.chips = response.data.chips;
		});

		} else {
			var message = 'Please Login first!';
			var id = Flash.create('danger', message, 3500);
			$location.path('/');
		};
	
	$scope.issue = {};	
	
	$scope.newCmt = function(){
		$http.post('http://wigig-584/plans/newcomment', {comment: $scope.issue, id: $routeParams})
		.then(function(response){
			if(response.data == 'success') {
				$location.path('/plans/'+$routeParams.planId);
				var message = 'Comment Was Added successfully';
				var id = Flash.create('success', message, 5000);
			} else {
				var message = response.data;
				var id = Flash.create('dange', message, 5000);
			}
		})
	}
}]);