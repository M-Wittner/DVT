myApp.controller('newLineupCtrl', ['$scope', '$http', '$location', 'Flash', 'Session', '$cookies', 'AuthService', '$window', 'testParams', function ($scope, $http, $location, Flash, Session, $cookies, AuthService, $window, testParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.testParams = testParams.lineups;
	var site = testParams.site;
	var $ctrl = this;
	var scope = $scope;
	$scope.isCollapsed = true;
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
	
	$scope.insertLineup = function(){
		$scope.lock = true;
		$scope.array.push(this.lineup);
		console.log(this.lineup);
	}
	

	$scope.copy = function(){
//		$scope.lineup = (scope.$parent.$$prevSibling.$$childTail.lineup);
		console.log(this);
	}
	
	$scope.editToggle = function(){
		$scope.array.splice(this.lineup, 1);;
		$scope.lock = false;
	}
	
	$scope.inRange = function(param, value){
		var value = parseInt(value);
		var range = Math.pow(2, param.parameter_range);
		if(value <= range && value >= 0){
			var returnClass = "has-success";
		}else if(value >= range || isNaN(value) || value < 0){
			var returnClass = "has-error";
		}
		return returnClass;
	}
	
	$scope.submit = function(){
		$http.post(site+'/lineups/create', {lineups: $scope.lineups, title: $scope.lineup.title , user: $scope.user})
		.then(function(response){
			console.log(response.data);
			//generate a temp <a/> tag
			var link = document.createElement("a");
			link.href = response.data;
			//set the visibility hidden so it will not effect on web-layout
			link.style = "visibility:hidden";
			// appending the anchor tag and remove it after automatic click
			document.body.appendChild(link);
			link.click();
			document.body.removeChild(link);
			
		});
//		console.log($scope.lineups);
	}

}]);