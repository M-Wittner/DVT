myApp.controller('newCommentCtrl', ['$scope', '$location','$http', '$routeParams', function ($scope, $location, $http, $routeParams) {
	$scope.issue = {};
	
	$http.post('http://wigig-584:3000/plans/edit', $routeParams)
	.then(function(response){
		console.log(response.data);
	});
	
	$scope.newCmt = function(){
		$http.post('http://wigig-584:3000/plans/newcomment', $scope.issue, $routeParams.id)
		.then(function(response){
			console.log(response.data);
		})
	}
}]);