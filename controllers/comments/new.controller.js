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
	
	$scope.comment = {};	
	
	$scope.newCmt = function(){
		$http.post('http://wigig-584/plans/newcomment', {comment: $scope.comment, id: $routeParams})
		.then(function(response){
			if(response.data == 'true') {
//				console.log(response.data);
				$location.path('/plans/'+$routeParams.planId);
				var message = 'Comment Was Added successfully';
				var id = Flash.create('success', message, 5000);
			} else {
				console.log(response.data);
				var message = response.data;
				var id = Flash.create('danger', message, 5000);
			}
		})
//		console.log(this);
	}
}]);