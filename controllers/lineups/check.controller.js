myApp.controller('checkLineupCtrl', ['$scope', '$http', '$location', 'Flash', 'Session', '$cookies', 'AuthService', '$window', 'testParams', 'FileUploader', function ($scope, $http, $location, Flash, Session, $cookies, AuthService, $window, testParams, FileUploader) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.testParams = testParams;
	var site = testParams.site;
	$scope.uploader = new FileUploader();
	$scope.lineup = {};
	$scope.checkLineup = function(){
		$http.post(site+'/lineups/check', $scope.lineup)
		.then(function(response){
			console.log(response.data);
			if(response.data){
				var message = response.data;
				var id = Flash.create('success', message, 0);
			}
		})
	}

}]);