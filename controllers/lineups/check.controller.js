myApp.controller('checkLineupCtrl', ['$scope', '$http', '$location', 'Flash', 'Session', '$cookies', 'AuthService', '$window', 'testParams', function ($scope, $http, $location, Flash, Session, $cookies, AuthService, $window, testParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.testParams = testParams;
	var site = testParams.site;
//	$scope.uploader = new FileUploader();
	$scope.lineup = {};
	
	$scope.clearMsgs = function(){
		Flash.clear();
	}
	
	$scope.checkLineup = function(){
		$http.post(site+'/lineups/check', $scope.lineup)
		.then(function(response){
			console.log(response.data);
			if(response.data == 'Lineup is OK!'){
				var message = response.data;
				var id = Flash.create('success', message, 0);
			}else if(typeof(response.data) == 'object'){
				var data = response.data
				var result = [];
				for(var key in data){
//					console.log(typeof(data[key]));
					if(typeof(data[key]) == 'object'){
						data[key].forEach(function(msg){
							var message = "<strong>"+key+"</strong>" + ": " + msg;
							var id = Flash.create('danger', message, 0);
						})
					}
				}
			}
		})
	}

}]);