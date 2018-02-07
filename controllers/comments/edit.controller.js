myApp.controller('editCommentCtrl', ['$scope', '$location','$http', '$routeParams', 'Flash', 'AuthService', 'testParams', function ($scope, $location, $http, $routeParams, Flash, AuthService, testParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	
	if($scope.isAuthenticated == true) {
		
		$http.post('http://wigig-584/plans/getComment', $routeParams)
		.then(function(response){
			$scope.comment = response.data.comment[0];
			$scope.comment.test_name = response.data.test[0].name;
			$scope.test = response.data.test[0];
			$scope.test.chips = response.data.chips;
//			console.log($scope.chips);
//			console.log(response.data);
		});

		} else {
			var message = 'Please Login first!';
			var id = Flash.create('danger', message, 3500);
			$location.path('/');
		};
	
	$scope.comment = {};
	$scope.testParams = testParams;
	$scope.newCmt = function(){
		$http.post('http://wigig-584/plans/editcomment', $scope.comment)
		.then(function(response){
			if(response.data == '1') {
//				console.log(response.data);
				$location.path('/plans/'+$routeParams.planId);
				var message = 'Comment Was Edited successfully';
				var id = Flash.create('success', message, 5000);
			} else {
				console.log(response.data);
				var message = response.data;
				var id = Flash.create('danger', message, 5000);
			}
//			$location.path('/plans/'+$routeParams.planId);
		})
	}
}]);