myApp.controller('lineupFormCtrl', ['$scope', '$http', '$location', 'Flash', 'Session', '$cookies', 'AuthService', '$window', 'testParams', function ($scope, $http, $location, Flash, Session, $cookies, AuthService, $window, testParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.testParams = testParams;
	var site = testParams.site;
	var $ctrl = this;
	$scope.user = {
		'userId' : $cookies.getObject('loggedUser').userId,
		'username' : $cookies.getObject('loggedUser').username,
	}
	$scope.lineup = {};
	$scope.array = [];
	$scope.insertLineup = function(data){
		$scope.array.push(this.lineup);
		console.log(this);
	}

}]);