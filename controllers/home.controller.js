myApp.controller('homeCtrl', ['$scope', '$rootScope', '$http', 'AuthService', 'AUTH_EVENTS', 'USER_ROLES', '$location', 'Flash','Session','$cookies', '$window', function ($scope, $rootScope, $http, AuthService, AUTH_EVENTS, USER_ROLES, $location,
Flash, Session, $cookies, $window, testParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	var site = $rootScope.site;
	$scope.isAuthorized = AuthService.isAuthorized;
	$scope.isAuthenticated = AuthService.isAuthenticated;
//	console.log($scope.isAuthenticated());
	
	if($cookies.getObject('loggedUser')){
		$scope.currentUser = $cookies.getObject('loggedUser');
	} else{
		$scope.currentUser = {};
		$scope.userRoles = USER_ROLES;
		
	}
//	console.log($scope.currentUser);
	$scope.user = {};
	$scope.login = function() {
		$http.post(site+'/home/login', $scope.user)
		.then(function(response){
			if(response.data != 'false'){
				$scope.currentUser = response.data;
				console.log($scope.currentUser);
				var date = new Date();
				date.setHours(date.getHours() + 3);
				$cookies.putObject('loggedUser', response.data, {'expires': date});
				$location.path('/today');
				var message = 'Hello, '+$scope.user.username+ '! Successfully logged in!';
				var id = Flash.create('success', message, 5000);
			}else{
				var message = 'Failed to log in';
				var id = Flash.create('danger', message, 5000);
				console.log('Failed to log in');
			}
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