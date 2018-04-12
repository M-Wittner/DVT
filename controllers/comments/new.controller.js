myApp.controller('newCommentCtrl', ['$scope', '$location','$http', '$routeParams', 'Flash', 'AuthService', 'testParams', function ($scope, $location, $http, $routeParams, Flash, AuthService, testParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	var site = testParams.site;
	if($scope.isAuthenticated == true) {
		
		$http.post(site+'/plans/newComment', $routeParams)
		.then(function(response){
			$scope.test = response.data.test;
			$scope.chips = response.data.chips;
			console.log(response.data);
		});

		} else {
			var message = 'Please Login first!';
			var id = Flash.create('danger', message, 3500);
			$location.path('/');
		};
	
	$scope.comment = {};
	$scope.comment.userId = $scope.currentUser.userId;

	$scope.newCmt = function(){
		$http.post(site+'/plans/addComment', {comment: this.comment, id: $routeParams})
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
//		console.log(this.comment);
	}
}]);