myApp.controller('newTaskCommentCtrl', ['$scope', '$location','$http', '$routeParams', '$cookies', 'Flash', 'AuthService', 'testParams', function ($scope, $location, $http, $routeParams, $cookies, Flash, AuthService, testParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	
	if($scope.isAuthenticated == true) {
		$scope.comment = {};
		$scope.comment.user_id = $cookies.getObject('loggedUser').userId;
		$scope.comment.task_id = $routeParams.id;
		var site = testParams.site;
		
		$scope.newCmt = function(){
			$http.post(site+'/tasks/newComment', {comment: this.comment, site: site})
			.then(function(response){
				if(response.data == 'Completed'){
					var message = 'Comment Created Succesfully!';
					var id = Flash.create('success', message, 3500);
					$location.path('/tasks/'+$routeParams.id);
				} else {
					var message = response.data;
					var id = Flash.create('danger', message, 3500);
					console.log(response.data);
				}
			})
		}
	}
}]);