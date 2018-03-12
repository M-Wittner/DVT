myApp.controller('newLineupCtrl', ['$scope', '$http', '$location', 'Flash', 'Session', '$cookies', 'AuthService', '$window', 'FileSaver', 'Blob', 'testParams', function ($scope, $http, $location, Flash, Session, $cookies, AuthService, $window, FileSaver, Blob, testParams) {
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
	
	$scope.tempAdd = false;
	$scope.addTemp = function(){
		$scope.tempAdd = !$scope.tempAdd;
	}
	
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
		$http.post(site+'/lineups/create', {lineups: $scope.lineups, user: $scope.user})
		.then(function(response){
			console.log(response);
//			var buffer = new Uint8Array(response.data);
//			var blob = new Blob([buffer], {type: 'application/vnd.ms-excel;charset=charset=utf-8'});
//			FileSaver.saveAs(blob, 'bla.xlsx', true);
		});
//		console.log($scope.lineups);
	}

}]);