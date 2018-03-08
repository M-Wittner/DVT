myApp.controller('newLineupCtrl', ['$scope', '$http', '$location', 'Flash', 'Session', '$cookies', 'AuthService', '$window', 'testParams', function ($scope, $http, $location, Flash, Session, $cookies, AuthService, $window, testParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.testParams = testParams.lineups;
	var site = testParams.site;
	var $ctrl = this;
	var scope = $scope;
	$scope.user = {
		'userId' : $cookies.getObject('loggedUser').userId,
		'username' : $cookies.getObject('loggedUser').username,
	}
	$scope.batches = [{}];
	$scope.lineups = [];
	$scope.lineup = {};
	
	$scope.addBatch = function(){
		$scope.batches.push({});
	}
	
	$scope.removeBatch = function(){
		$scope.batches.splice($scope.batches.length-1, 1);
	}
	
	$scope.insertLineup = function(data){
		$scope.lock = true;
		$scope.array.push(this.lineup);
	}
	
	$scope.editToggle = function(){
		$scope.array.splice(this.lineup, 1);;
		$scope.lock = false;
	}
	
	$scope.submit = function(){
//		$http.post(site+'/lineups/create', {lineups: $scope.lineups, user: $scope.user})
//		.then(function(response){
//			console.log(response.data);
//		});
		console.log($scope.lineups);
	}

}]);