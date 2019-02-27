myApp.controller('appCtrl', ['$scope', 'USER_ROLES', 'AuthService', function ($scope, USER_ROLES, AuthService) {
	$scope.currentUser = null;
	$scope.userRolse = USER_ROLES;
	$scope.isAuthorized = AuthService.isAuthorized;
	
	$scope.setCurrentUser = function(user){
		$scope.currentUser = user;
	};
}]);