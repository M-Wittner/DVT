myApp.controller('homeCtrl', ['$scope', '$rootScope', '$http', 'AuthService', 'AUTH_EVENTS', 'USER_ROLES', function ($scope, $rootScope, $http, AuthService, AUTH_EVENTS, USER_ROLES) {
	$scope.user = {};
	$scope.login = function (user) {
		console.log(user);
		AuthService.login($scope.user).then(function (user) {
			$rootScope.$broadcast(AUTH_EVENTS.loginSuccess);
			$scope.setCurrentUser(user);
			console.log('fata');
		}, function () {
			$rootScope.$broadcast(AUTH_EVENTS.loginFailed);
		});
	};

	$scope.currentUser = null;
	$scope.userRoles = USER_ROLES;
	$scope.isAuthorized = AuthService.isAuthorized;

	$scope.setCurrentUser = function (user) {
		$scope.currentUser = user;
	};
}]);