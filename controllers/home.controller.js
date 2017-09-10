myApp.controller('homeCtrl', ['$scope', '$rootScope', '$http', 'AuthService', 'AUTH_EVENTS', 'USER_ROLES', '$location', 'Flash','Session','$cookies', '$window', function ($scope, $rootScope, $http, AuthService, AUTH_EVENTS, USER_ROLES, $location,
Flash, Session, $cookies, $window) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	
	if($cookies.getObject('loggedUser')){
		$scope.currentUser = $cookies.getObject('loggedUser');
	} else{
		$scope.currentUser = {};
		$scope.userRoles = USER_ROLES;
		$scope.isAuthorized = AuthService.isAuthorized;
		$scope.setCurrentUser = function (user) {
			$scope.currentUser.username = user.username;
		};
	}
	$scope.user = {};
	$scope.login = function (user) {
		AuthService.login($scope.user).then(function (user) {
			$rootScope.$broadcast(AUTH_EVENTS.loginSuccess);
			$scope.setCurrentUser(user);
			$scope.currentUser = user;
			if(user.username){
				// Set Expire Time To 3 Hours
				var date = new Date();
 				date.setDate(date.getDate() + 0.125);
				$cookies.putObject('loggedUser', user, {'expires': date});
				$window.location.reload();
				$location.path('/plans');
				var message = 'Welcome, '+ user.username + '!';
				var id = Flash.create('success', message, 5000);
				console.log(date);
			} else {
				var message = 'Failed to log in';
				var id = Flash.create('danger', message, 5000);
//				console.log('Failed to log in');
			}
			
		}, function () {
			$rootScope.$broadcast(AUTH_EVENTS.loginFailed);
		});
	};
	

	
	$scope.logout = function(){
		$cookies.remove('loggedUser');
		$window.location.reload();
		$location.path('/');
		var message = 'Logged out successfully';
		var id = Flash.create('success', message, 5000);
	};


}]);